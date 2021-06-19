<template>
    <b-modal id="modal-prevent-closing" ref="modal" :title="titleDialog" @show="resetModal" @hidden="resetModal" @ok="handleOk" size="lg">
        <form ref="form" @submit.stop.prevent="handleSubmit">
            <b-container>
                <b-row>
                    <b-col>
                        <b-form-group label="Fecha" label-for="date-input" :invalid-feedback="errors.date" :state="false">
                            <!-- <b-form-datepicker id="date-input" v-model="form.date" :state="errors.date" locale="es" required class="mb-2" size="sm"></b-form-datepicker> -->
                            <b-form-input type="date" v-model="form.date" :state="errors.date" size="sm"/>
                        </b-form-group>
                    </b-col>
                    <b-col>
                        <b-form-group label="Hora de Inicio" label-for="start_time-input" :invalid-feedback="errors.start_time" :state="false">
                            <!-- <b-form-timepicker v-model="form.start_time" :state="errors.start_time" locale="en" size="sm"></b-form-timepicker> -->
                            <b-form-input type="time" v-model="form.start_time" :state="errors.start_time" size="sm"/>
                        </b-form-group>
                    </b-col>
                    <b-col>
                        <b-form-group label="Hora de Termino" label-for="end_time-input" :invalid-feedback="errors.end_time" :state="false">
                            <!-- <b-form-timepicker v-model="form.end_time" :state="errors.end_time" locale="en" size="sm"></b-form-timepicker> -->
                            <b-form-input type="time" v-model="form.end_time" :state="null" size="sm"/>
                        </b-form-group>
                    </b-col>
                </b-row>
            </b-container>
            <b-container>
                <b-row>
                    <b-col>
                        <b-form-group label="Cliente" label-for="client_ide-input" :invalid-feedback="errors.client_id" :state="false">
                            <b-form-select v-model="form.client_id" :options="clients" :state="errors.client_id" value-field="id" text-field="description" size="sm" required></b-form-select>
                        </b-form-group>
                    </b-col>
                </b-row>
            </b-container>
            <b-container>
                <b-row>
                    <b-col>
                        <b-form-group label="Cuenta" label-for="client_ide-input" :invalid-feedback="errors.category_id" :state="false">
                            <b-form-select v-model="form.category_id" :options="accounts" :state="errors.category_id" value-field="id" text-field="description" size="sm" required></b-form-select>
                        </b-form-group>
                    </b-col>
                    <b-col>
                        <b-form-group label="Centro de Costos" label-for="client_ide-input" :invalid-feedback="errors.service_id" :state="false">
                            <b-form-select v-model="form.service_id" :options="costs" :state="errors.service_id" value-field="id" text-field="description" size="sm" required></b-form-select>
                        </b-form-group>
                    </b-col>
                    <b-col>
                        <b-form-group label="Periodo" label-for="end_time-input" :invalid-feedback="errors.period" :state="false">
                            <!-- <b-form-datepicker v-model="form.period" :state="errors.period" locale="en" size="sm"></b-form-datepicker> -->
                            <div class="input-group input-group-sm mb-3">
                                <input type="month" class="form-control" v-model="form.period" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm">
                            </div>
                        </b-form-group>
                    </b-col>
                </b-row>
            </b-container>
            <b-container>
                <b-row>
                    <b-col>
                        <b-form-group label="Descripción" label-for="client_ide-input" :invalid-feedback="errors.description" :state="false">
                            <b-form-textarea trimid="textarea" v-model="form.description" :state="errors.description" placeholder="Ingrese su tareo ..."></b-form-textarea>
                        </b-form-group>
                    </b-col>
                </b-row>
            </b-container>
            <b-container>
                <b-row>
                    <b-col>
                        <b-form-group label="Descripción" label-for="client_ide-input">
                            <!-- <b-form-rating v-model="form.status" change="" :state="errors.status"></b-form-rating> -->
                            <b-form-input v-model="form.status" type="range" min="0" max="100" step="25"></b-form-input>
                            <!-- <b-form-input v-model="form.status" :type="number"></b-form-input> -->
                        </b-form-group>
                    </b-col>
                </b-row>
            </b-container>
        </form>
    </b-modal>
</template>

<script>
import {serviceNumber} from '../../mixins/functions'
export default {
    mixins: [serviceNumber],
    props: ["recordId"],
    data() {
        return {
            loading_submit: false,
            resource: "binnacles",
            titleDialog: null,
            form: {},
            clients: [],
            accounts: [],
            costs: [],
            errors: {},
        };
    },

    async created() {
        await this.$http.get(`/${this.resource}/tables`).then((resindex) => {
                this.clients = resindex.data.clients;
                this.accounts = resindex.data.accounts;
                this.costs = resindex.data.costs;
            }
        );        
    },

    computed: {
        
    },

    methods: {
        create(){
            if (this.recordId) {
                axios.get(`/${this.resource}/record/${this.recordId}`)
                    .then(response => {
                        this.form = response.data.data
                    })
            }else{
                 this.form.date = moment().format("YYYY-MM-DD")
                 this.form.period = moment().format("YYYY-MM")
                 this.form.status = 50
            }

            this.titleDialog = (this.recordId)? 'Editar Evento':'Nuevo Evento'
        },
        checkFormValidity() {
            const valid = this.$refs.form.checkValidity()
            this.errors = valid
            return valid
        },
        resetModal() {
            this.form = {}
            this.errors = {}
            this.create()
        },
        handleOk(bvModalEvt) {
            // Prevent modal from closing
            bvModalEvt.preventDefault()
            // Trigger submit handler
            this.handleSubmit()
        },
        handleSubmit() {
            axios.post(`/${this.resource}`, this.form).then(response => {
                if (response.data.success) {
                    // console.log(response)
                    this.$bvToast.toast(response.data.message, {
                        title: response.data.type,
                        variant: 'success',
                        solid: true
                    })

                    this.$nextTick(() => {
                        this.$bvModal.hide('modal-prevent-closing')
                    })

                    this.$eventHub.$emit('reloadData')

                } else {
                    this.$bvToast.toast(response.data.message, {
                        title: response.data.type,
                        variant: 'danger',
                        solid: true
                    })
                }
            }).catch(error => {
                if (error.response.status === 422) {
                    this.errors = error.response.data.errors

                } else {
                    this.$message.error(error.response.data.message)
                }
            })
        }
    }
};
</script>