<template>
    <b-modal id="modal-prevent-closing" ref="modal" title="Parte Diario" @show="resetModal" @hidden="resetModal" @ok="handleOk" size="lg">
        <form ref="form" @submit.stop.prevent="handleSubmit">
            <b-container>
                <b-row>
                    <b-col>
                        <b-form-group label="Fecha" label-for="date-input" invalid-feedback="Código is required" :state="dateState">
                            <b-form-datepicker id="date-input" v-model="form.date" :state="dateState" locale="es" required class="mb-2" size="sm"></b-form-datepicker>
                        </b-form-group>
                    </b-col>
                    <b-col>
                        <b-form-group label="Hora de Inicio" label-for="start_time-input" invalid-feedback="Descripción is required" :state="start_timeState">
                            <b-form-timepicker v-model="form.start_time" :state="start_timeState" locale="en" size="sm"></b-form-timepicker>
                        </b-form-group>
                    </b-col>
                    <b-col>
                        <b-form-group label="Hora de Termino" label-for="end_time-input" invalid-feedback="Descripción is required" :state="end_timeState">
                            <b-form-timepicker v-model="form.end_time" :state="end_timeState" locale="en" size="sm"></b-form-timepicker>
                        </b-form-group>
                    </b-col>
                </b-row>
            </b-container>
            <b-container>
                <b-row>
                    <b-col>
                        <b-form-group label="Cliente" label-for="client_ide-input" invalid-feedback="Descripción is required" :state="client_idState">
                            <b-form-select v-model="form.client_id" :options="clients" value-field="id" text-field="description" size="sm" required></b-form-select>
                        </b-form-group>
                    </b-col>
                </b-row>
            </b-container>
            <b-container>
                <b-row>
                    <b-col>
                        <b-form-group label="Cuenta" label-for="client_ide-input" invalid-feedback="Descripción is required" :state="client_idState">
                            <b-form-select v-model="form.category_id" :options="accounts" value-field="id" text-field="description" size="sm" required></b-form-select>
                        </b-form-group>
                    </b-col>
                    <b-col>
                        <b-form-group label="Centro de Costos" label-for="client_ide-input" invalid-feedback="Descripción is required" :state="client_idState">
                            <b-form-select v-model="form.service_id" :options="costs" value-field="id" text-field="description" size="sm" required></b-form-select>
                        </b-form-group>
                    </b-col>
                    <b-col>
                        <b-form-group label="Periodo" label-for="end_time-input" invalid-feedback="Descripción is required" :state="end_timeState">
                            <b-form-datepicker v-model="form.period" :state="end_timeState" locale="en" size="sm"></b-form-datepicker>
                        </b-form-group>
                    </b-col>
                </b-row>
            </b-container>
            <b-container>
                <b-row>
                    <b-col>
                        <b-form-group label="Descripción" label-for="client_ide-input" invalid-feedback="Descripción is required" :state="client_idState">
                            <b-form-textarea trimid="textarea" v-model="form.description" placeholder="Ingrese su tareo ..."></b-form-textarea>
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
            form: {},
            clients: [],
            accounts: [],
            costs: [],
            dateState: null,
            start_timeState: null,
            end_timeState: null,
            client_idState: null,
            submittedNames: [],
            errors: {},
        };
    },
    async created() {
        await this.$http.get(`/${this.resource}/tables`).then((resindex) => {
                // this.countries = resindex.data.countries;
                this.clients = resindex.data.clients;
                this.accounts = resindex.data.accounts;
                this.costs = resindex.data.costs;
                // this.identity_document_types = resindex.data.identity_document_types;
                // this.locations = resindex.data.locations;
                // this.person_types = resindex.data.person_types;
                // this.perPage = resindex.data.meta.per_page;
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
            }
        },
        checkFormValidity() {
            const valid = this.$refs.form.checkValidity()
            this.nameState = valid
            this.codeState = valid
            return valid
        },
        resetModal() {
            this.form = {}
            this.dateState = null
            this.start_timeState = null
            this.client_idState = null
            this.create()
            
        },
        handleOk(bvModalEvt) {
            // Prevent modal from closing
            bvModalEvt.preventDefault()
            // Trigger submit handler
            this.handleSubmit()
        },
        handleSubmit() {
            // Exit when the form isn't valid
            if (!this.checkFormValidity()) {
                return
            }
            // Push the name to submitted names
            axios.post(`/${this.resource}`, this.form)
                .then(response => {
                    if (response.data.success) {
                        console.log(response)
                        this.$bvToast.toast(response.data.message, {
                            title: response.data.type,
                            variant: 'success',
                            solid: true
                        })
                        this.$eventHub.$emit('reloadData')
                    } else {
                        this.$bvToast.toast(response.data.message, {
                            title: response.data.type,
                            variant: 'danger',
                            solid: true
                        })
                    }
                })
            // Hide the modal manually
            this.$nextTick(() => {
                this.$bvModal.hide('modal-prevent-closing')
            })
        }
    }
};
</script>