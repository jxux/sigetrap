<template>
    <b-input-group size="sm">
        <b-form-input :value="value" :maxlength="maxLength" @input="handleInput($event)" show-word-limit></b-form-input>
        <template #append>
            <b-button   variant="outline-success" 
                        :loading="loading"
                        icon="el-icon-search"
                        @click.prevent="clickSearch">{{ buttonText }}
            </b-button>
        </template>
    </b-input-group>
</template>
<script type="text/javascript">
    export default {
        props: {
            identity_document_type_id: {
                required: true,
                type: String
            },
            value: {
                required: true,
                type: String,
                default: ''
            }
        },
        data() {
            return {
                loading: false,
                resource_base: 'service',
                resource: null,
                maxLength: 20,
                buttonText: null
            }
        },
        created(){
            this.changeIdentityDocumentTypeId()
        },
        mounted(){
            this.$eventHub.$on('enableClickSearch',()=>{
                this.clickSearch()
            })
        },
        watch: {
            identity_document_type_id() {
                this.changeIdentityDocumentTypeId()
            },
        },
        methods: {
            changeIdentityDocumentTypeId() {
                this.buttonText = null;
                if(this.identity_document_type_id === '6') {
                    this.maxLength = 11;
                    this.buttonText = 'SUNAT';
                    this.resource = this.resource_base+'/ruc';
                }
                if(this.identity_document_type_id === '1') {
                    this.maxLength = 8;
                    this.buttonText = 'RENIEC';
                    this.resource = this.resource_base+'/dni';
                }
                if(this.identity_document_type_id !== '6' && this.identity_document_type_id !== '1') {
                    this.maxLength = 20
                }
            },
            handleInput (value) {
                this.$emit('input', value)
            },
            clickSearch() {
                this.loading = true;
                axios.get(`/${this.resource}/${this.value}`)
                    .then(response => {
                        let res = response.data;
                        if (res.success) {
                            this.$emit('search', res.data)
                        } else {
                           this.$bvToast.toast(res.message, {
                                        title: res.data.type,
                                        variant: 'danger',
                                        solid: true
                                    }
                            )
                        }
                    })
                    .catch(error => {
                        console.log(error.response)
                    })
                    .then(() => {
                        this.loading = false
                    })
            }
        }
    }
</script>