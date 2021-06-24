<template>
    <b-modal id="modal-prevent-closing" ref="modal" title="Cuentas" @show="resetModal" @hidden="resetModal" @ok="handleOk">
        <form ref="form" @submit.stop.prevent="handleSubmit">
            <b-form-group label="Code" label-for="name-input" invalid-feedback="Código is required" :state="codeState">
                <b-form-input id="code-input" v-model="form.code" :state="codeState" required></b-form-input>
            </b-form-group>
            <b-form-group label="Name" label-for="name-input" invalid-feedback="Descripción is required" :state="nameState">
                <b-form-input id="name-input" v-model="form.name" :state="nameState" required></b-form-input>
            </b-form-group>
        </form>
    </b-modal>    
</template>

<script>
import {serviceNumber} from '../../../mixins/functions'
export default {
    mixins: [serviceNumber],
    props: ["recordId"],
    data() {
        return {
            loading_submit: false,
            resource: "accounts",
            form: {},
            nameState: null,
            codeState: null,
            submittedNames: [],
            errors: {},
        };
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
            this.nameState = null
            this.codeState = null
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