<template>
    <!-- Pagina index -->
    <div v-if="!is_creating">
        <div class="d-flex flex-row bd-highlight mb-3">
            <h2 class="mb-0">Contatos</h2>
            <button v-on:click="enableCreate" type="button" class="btn btn-success ml-5" title="Cadastrar">Cadastrar</button>
        </div>
        <table class="table table-striped">
            <thead>
            <tr>
                <th>Nome</th>
                <th>Email</th>
                <th>Facebook</th>
                <th>LinkedIn</th>
                <th>Ações</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="contact in contacts">
                <td>{{contact.nome}}</td>
                <td>{{contact.email}}</td>
                <td>{{contact.facebook}}</td>
                <td>{{contact.linkedin}}</td>
                <td>
                    <button @click="edit(contact.id)" type="button" class="btn btn-warning col-md-5" title="Editar">
                        <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                    </button>
                    <button @click="destroy(contact.id)" type="button" class="btn btn-danger col-md-5" title="Excluir">
                        <i class="fa fa-ban" aria-hidden="true"></i>
                    </button>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
    <!-- Pagina Create/Edit -->
    <div v-else>
        <div class="d-flex flex-row bd-highlight mb-3">
            <h2 class="mb-0" v-if="!new_contact.id">Novo Contato</h2>
            <h2 class="mb-0" v-else>Atualizar Contato: {{new_contact.nome}}</h2>
        </div>
        <form>
            <div class="form-group row">
                <div class="col-md-6">
                    <label for="nome">Nome</label>
                    <input v-model="new_contact.nome" type="text" class="form-control" id="nome">
                </div>

                <div class="col-md-6">
                    <label for="email">Email</label>
                    <input v-model="new_contact.email" type="text" class="form-control" id="email">
                </div>

                <div class="col-md-6">
                    <label for="facebook">Facebook</label>
                    <input v-model="new_contact.facebook" type="text" class="form-control" id="facebook">
                </div>

                <div class="col-md-6">
                    <label for="linkedin">LinkedIn</label>
                    <input v-model="new_contact.linkedin" type="text" class="form-control" id="linkedin">
                </div>

            </div>
            <hr/>
            <div class="row">
                <div class="col-md-12">
                    <h3>Telefones</h3>
                </div>
                <div class="row col-md-12 form-group" v-for="tel in new_contact.tels">
                    <div class="col-md-4">
                        <label for="numero">Número</label>
                        <input v-model="tel.numero" type="tel" class="form-control" id="numero">
                    </div>
                    <div class="col-md-4">
                        <label for="tipo">Tipo</label>
                        <select class="form-control" v-model="tel.tipo" id="tipo">
                            <option>Residencial</option>
                            <option>Comercial</option>
                            <option>Celular</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="col-md-12 text-center" for="btn-rm">Remover Telefone</label>
                        <button @click="removeTel(tel)" type="button" class="btn btn-danger col-md-4 offset-md-4"
                                title="Remover telefone" id="btn-rm">
                            <i class="fa fa-times" aria-hidden="true"></i>
                        </button>
                    </div>
                </div>
                <div class="col-md-12">
                    <label class="col-md-3" for="btn-add">Adicionar Telefone</label>
                    <button @click="addTel()" type="button" class="btn btn-success col-md-1" title="Adicionar telefone"
                            id="btn-add">
                        <i class="fa fa-plus" aria-hidden="true"></i>
                    </button>
                </div>
            </div>
            <hr/>
            <div class="d-flex flex-row bd-highlight mb-3 mt-5">
                <button type="button" v-on:click="disableCreate" class="btn btn-light">Cancelar</button>
                <button type="button" v-on:click="create" class="btn btn-success">Cadastrar</button>
            </div>
        </form>
        <div class="alert-danger">
            <h6 v-for="validation in validations">• {{validation}}</h6>
        </div>
    </div>
</template>

<script>
    import Vue from 'vue';
    import VueSweetalert2 from 'vue-sweetalert2';
    import 'sweetalert2/dist/sweetalert2.min.css';

    Vue.use(VueSweetalert2);
    export default {
        props: [
            'baseRoute'
        ],
        data(){
            return {
                // Variavel para controlar a pagina exibida
                is_creating: false,
                // Variaveis da pagina 'index'
                contacts: [],
                // Variaveis da pagina 'create/edit'
                new_contact: {
                    id: null,
                    nome: "",
                    email: "",
                    facebook: "",
                    linkedin: "",
                    tels: []
                },
                validations: []
            }
        },
        methods: {
            // Metodos da pagina 'index'
            enableCreate: function() {
                this.clearContact();
                this.is_creating = true;
            },
            getAll: function() {
                axios.get(this.baseRoute + `/contacts`).then((response) => {
                    this.contacts = response.data;
                }).catch((err) => {
                    console.log('err', err);
                    this.$swal('Erro', 'Não foi possível recuperar os clientes', "error");
                });
            },
            edit: function(id) {
                axios.get(this.baseRoute + `/contacts.getById?id=${id}`).then((response) => {
                    this.enableCreate();
                    this.new_contact = response.data;
                }).catch((err) => {
                    console.log('err', err);
                    this.$swal('Error', 'Erro ao buscar cliente', "error");
                });
            },
            update: function() {
                axios.put(this.baseRoute + '/contacts', {
                    contact: this.new_contact
                }).then((response) => {
                    if (response.data.ok) {
                        this.$swal('Sucesso', 'Registro atualizado com sucesso', "success");
                        this.disableCreate();
                        this.getAll();
                        this.clearContact();
                    }
                }).catch((err) => {
                    console.log('err', err);
                    this.$swal('Error', 'Erro ao atualizar cliente', "error");
                });
            },
            destroy: function(id) {
                this.$swal({
                    title: "Deseja excluir este contato?",
                    text: "Esta ação não pode ser desfeita!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    confirmButtonText: "Sim!"
                }).then((result) => {
                    if (result.value) {
                        axios.delete(this.baseRoute + `/contacts?id=${id}`).then(() => {
                            this.getAll();
                            this.$swal('Sucesso', 'Registro excluído com sucesso', "success");
                        }).catch((err) => {
                            console.log('err', err);
                            this.$swal('Error', 'Erro ao buscar cliente', "error");
                        });
                    }
                });
            },
            // Metodos da pagina 'create/edit'
            disableCreate: function() {
                this.is_creating = false;
            },
            create: function() {
                this.validations = [];

                // Faz a validacao dos campos
                if (!this.validateEmptyOrWhiteSpace(this.new_contact.nome)) {
                    this.validations.push("Preencha o campo Nome.");
                }
                if (!this.validateEmptyOrWhiteSpace(this.new_contact.email)) {
                    this.validations.push("Preencha o campo Email.");
                }

                // Se passar nas validacoes
                // se ja tiver id atualiza, senao insere
                if (this.validations.length === 0) {
                    if (this.new_contact.id) {
                        this.update();
                    } else {
                        this.store();
                    }
                }
            },
            store: function() {
                axios.post(this.baseRoute + `/contacts`, {
                    contact: this.new_contact
                }).then((response) => {
                    if (response.data.ok) {
                        this.$swal('Sucesso', 'Registro inserido com sucesso', "success");
                        this.disableCreate();
                        this.getAll();
                        this.clearContact();
                    }
                }).catch((err) => {
                    console.log('err', err);
                    this.$swal('Error', 'Erro ao inserir cliente', "error");
                });
            },
            addTel: function() {
                this.new_contact.tels.push({id: null, numero: '', tipo: 'Residencial'});
            },
            removeTel: function(tel) {
                const index = this.new_contact.tels.indexOf(tel);
                this.new_contact.tels.splice(index, 1);
            },
            validateEmptyOrWhiteSpace: function(txt) { // Valida se o texto nao esta vazio nem somente espacos em branco
                return txt === null || txt.trim().length>0;
            },
            clearContact: function() {
                this.new_contact = {
                    id: null,
                    nome: "",
                    email: "",
                    facebook: "",
                    linkedin: "",
                    tels: []
                }
            }
        },
        mounted() {
            if (!this.is_creating) {
                this.getAll();
            }
        },
    }
</script>

<style scoped>
</style>
