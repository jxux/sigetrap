<template>
    <b-modal id="modal-prevent-closing" ref="modal" :title="titleDialog" @show="resetModal" @hidden="resetModal" @ok="handleOk" size="xl">
        <form @submit.stop.prevent="handleSubmit">
            <b-container>
                <b-row>
                    <b-col>
                        <b-form-group label="Tipo Doc. Identidad: ">
                            <b-form-select v-model="form.identity_document_type_id" :options="identity_document_types" value-field="id" text-field="description" size="sm" required></b-form-select>
                        </b-form-group>
                    </b-col>
                    <b-col>
                        <b-form-group label="Número: ">
                            <x-input-service :identity_document_type_id="form.identity_document_type_id" v-model="form.dni" @search="searchNumber"></x-input-service>
                        </b-form-group>
                    </b-col>
                    <b-col>
                        <b-form-group label="Nombre: ">
                            <b-form-input size="sm" v-model="form.name" required></b-form-input>
                        </b-form-group>
                    </b-col>
                    <b-col>
                        <b-form-group label="Nick: ">
                            <b-form-input size="sm" v-model="form.nick_name"></b-form-input>
                        </b-form-group>
                    </b-col>
                </b-row>
            </b-container>

            <b-container>
                <b-row>
                    <b-col cols="2">
                        <b-form-group label="País: ">
                            <b-form-select v-model="form.country_id" :options="countries" value-field="id" text-field="description" size="sm" required></b-form-select>
                        </b-form-group>
                    </b-col>

                    <b-col cols="2">
                        <b-form-group label="Departamento: ">
                            <b-form-select v-model="form.department_id" :options="all_departments" @input="filterProvince" value-field="id" text-field="description" size="sm"></b-form-select>
                        </b-form-group>
                    </b-col>
                    <b-col cols="2">
                        <b-form-group label="Provincia: ">
                            <b-form-select v-model="form.province_id" :options="all_provinces" @input="filterDistrict" value-field="id" text-field="description" size="sm"></b-form-select>
                        </b-form-group>
                    </b-col>
                    <b-col cols="2">
                        <b-form-group label="Distrito: ">
                            <b-form-select v-model="form.district_id" :options="all_districts" value-field="id" text-field="description" size="sm"></b-form-select>
                        </b-form-group>
                    </b-col>
                    <b-col>
                        <b-form-group label="Dirección: ">
                            <b-form-input size="sm" v-model="form.address"></b-form-input>
                        </b-form-group>
                    </b-col>
                </b-row>
            </b-container>

            <b-container>
                <b-row>
                    <b-col>
                    <b-form-group label="Genero" v-slot="{ ariaDescribedby }">
                        <b-form-radio-group
                            id="btn-radios-2"
                            v-model="form.sex"
                            :options="sex"
                            :aria-describedby="ariaDescribedby"
                            button-variant="outline-primary"
                            size="sm"
                            name="radio-btn-outline"
                            buttons>
                        </b-form-radio-group>
                    </b-form-group>
                    </b-col>
                    <b-col>
                        <b-form-group label="Fecha de Nacimiento" :invalid-feedback="errors.date_birth" :state="false">
                            <b-form-input type="date" v-model="form.date_birth" :state="errors.date_birth" size="sm"/>
                        </b-form-group>
                    </b-col>
                    <b-col>
                        <b-form-group label="Teléfono: ">
                            <b-form-input size="sm" v-model="form.telephone"></b-form-input>
                        </b-form-group>
                    </b-col>
                    <b-col>
                        <b-form-group label="Correo electrónico: ">
                            <b-form-input size="sm" v-model="form.email"></b-form-input>
                        </b-form-group>
                    </b-col>
                </b-row>
            </b-container>

            <b-container>
                <b-row>
                    <b-col>
                        <b-form-group label="Contraseña: ">
                            <b-form-input type="password" size="sm" v-model="form.password"></b-form-input>
                        </b-form-group>
                    </b-col>
                    <b-col>
                        <b-form-group label="Rol: ">
                            <b-form-checkbox-group
                                v-model="form.roles"
                                :options="roles"
                                value-field="id"
                                text-field="name"
                                name="flavour-1">
                            </b-form-checkbox-group>
                        </b-form-group>
                    </b-col>
                </b-row>
            </b-container>
            {{form}}
        </form>
    </b-modal>
</template>

<script>
import {serviceNumber} from '../../../mixins/functions'
export default {
    mixins: [serviceNumber],
    props: ["showDialog", "recordId", 'input_person'],
    data() {
        return {
            loading_submit: false,
            resource: "users",
            titleDialog: null,
            name: "",
            form: {},
            roles:[],
            sex: [
                { text: 'Femenino', value: 'F' },
                { text: 'Masculino', value: 'M' },
            ],
            errors: {},
            api_service_token:false,
            countries: [],
            all_departments: [],
            all_provinces: [],
            all_districts: [],
            provinces: [],
            districts: [],
            locations: [],
            person_types: [],
            identity_document_types: []
        };
    },
    async created() {
        await this.initForm()
        await this.$http.get(`/${this.resource}/tables`).then((resindex) => {
                this.countries = resindex.data.countries;
                this.all_departments = resindex.data.departments;
                this.all_provinces = resindex.data.provinces;
                this.all_districts = resindex.data.districts;
                this.identity_document_types = resindex.data.identity_document_types;
                this.locations = resindex.data.locations;
                this.roles = resindex.data.roles;
                this.person_types = resindex.data.person_types;
                // this.perPage = resindex.data.meta.per_page;
            }
        );
    },
    computed: {
        maxLength: function () {
            if (this.form.identity_document_type_id === '6') {
                return 11
            }
            if (this.form.identity_document_type_id === '1') {
                return 8
            }
        }
    },
    methods: {
        initForm() {
            this.errors = {}
            this.form = {
                id: null,
                identity_document_type_id: '1',
                number: '',
                name: null,
                trade_name: null,
                country_id: 'PE',
                department_id: null,
                province_id: null,
                district_id: null,
                address: null,
                telephone: null,
                email: null,
                person_type_id:null,
                comment:null,
                roles: null,
                addresses: [],
            }
        },
        async opened(){

            if(this.external && this.input_person) {
                if(this.form.number.length === 8 || this.form.number.length === 11){
                    if(this.api_service_token != false){
                        await this.$eventHub.$emit('enableClickSearch')
                    }else{
                        this.searchCustomer()
                    }
                }
            }

        },
        create(){
            // console.log(this.input_person)
            if(this.external) {
                if(this.document_type_id === '01') {
                    this.form.identity_document_type_id = '6'
                }
                if(this.document_type_id === '03') {
                    this.form.identity_document_type_id = '1'
                }

                if(this.input_person) {
                    this.form.identity_document_type_id = (this.input_person.identity_document_type_id) ? this.input_person.identity_document_type_id: this.form.identity_document_type_id
                    this.form.number = (this.input_person.number) ? this.input_person.number:''
                }
            }
            if (this.recordId) {
                axios.get(`/${this.resource}/record/${this.recordId}`)
                    .then(response => {
                        this.form = response.data.data
                        
                        if (response.data.data.contact == null) {
                            this.form.contact = {
                                full_name: null,
                                phone: null,
                            }
                        }
                        this.filterProvinces()
                        this.filterDistricts()
                    })
            }

            this.titleDialog = (this.recordId)? 'Editar Usuario':'Nuevo Usuario'
        },

        checkFormValidity() {
            const valid = this.$refs.form.checkValidity()
            this.nameState = valid
            this.codeState = valid
            return valid
        },
        resetModal() {
            // this.form = {}
            // this.nameState = null
            // this.codeState = null
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
            // if (!this.checkFormValidity()) {
            //     return
            // }
            // Push the name to submitted names
            axios.post(`/${this.resource}`, this.form)
                .then(response => {
                    if (response.data.success) {
                        // console.log(response)
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
        },

        clickAddAddress(){
            /* this.form.more_address.push({
                location_id: [],
                address: null,
            })*/

            this.form.addresses.push({
                'id': null,
                'country_id': 'PE',
                'location_id': [],
                'address': null,
                'email': null,
                'phone': null,
                'main': false,
            });
        },
        validateDigits(){
            const pattern_number = new RegExp('^[0-9]+$', 'i');
            if (this.form.identity_document_type_id === '6') {
                if(this.form.number.length !== 11){
                    return {
                        success: false,
                        message: `El campo número debe tener 11 dígitos.`
                    }
                }
                if(!pattern_number.test(this.form.number)){
                    return {
                        success: false,
                        message: `El campo número debe contener solo números`
                    }
                }
            }

            if (this.form.identity_document_type_id === '1') {
                if(this.form.number.length !== 8){
                    return {
                        success: false,
                        message: `El campo número debe tener 8 dígitos.`
                    }
                }

                if(!pattern_number.test(this.form.number)){
                    return {
                        success: false,
                        message: `El campo número debe contener solo números`
                    }
                }
            }

            if(['4', '7', '0'].includes(this.form.identity_document_type_id)){
                const pattern = new RegExp('^[A-Z0-9\-]+$', 'i');
                if(!pattern.test(this.form.number)){
                    return {
                        success: false,
                        message: `El campo número no cumple con el formato establecido`
                    }
                }
            }
            return {
                success: true
            }
        },
        async submit(){
            let val_digits = await this.validateDigits()
            if(!val_digits.success){
                return this.$bvToast.toast(val_digits.message, {
                        title: res.data.type,
                        variant: 'danger',
                        solid: true
                    }
                )
            }

            this.loading_submit = true

            await axios.post(`/${this.resource}`, this.form)
                .then(response => {
                    if (response.data.success) {
                        console.log(response)
                        this.$bvToast.toast(response.data.message, {
                            title: res.data.type,
                            variant: 'danger',
                            solid: true
                        })
                        if (this.external) {
                            this.$eventHub.$emit('reloadDataPersons', response.data.id)
                        } else {
                            this.$eventHub.$emit('reloadData')
                        }
                        this.close()
                    } else {
                        this.$bvToast.toast(response.data.message, {
                            title: res.data.type,
                            variant: 'danger',
                            solid: true
                        })
                    }
                })
                .catch(error => {
                    if (error.response.status === 422) {
                        this.errors = error.response.data
                    } else {
                        console.log(error)
                    }
                })
                .then(() => {
                    this.loading_submit = false
                })
        },
        changeIdentityDocType(){
            (this.recordId == null) ? this.setDataDefaultCustomer() : null
        },
        setDataDefaultCustomer(){
            if(this.form.identity_document_type_id == '0'){
                this.form.number = '99999999'
                this.form.name = "Clientes - Varios"
            }else{
                this.form.number = ''
                this.form.name = null
            }
        },
        close(){
            this.$eventHub.$emit('initInputPerson')
            this.$emit('update:showDialog', false)
            this.initForm()
        },
        searchCustomer(){
            this.searchServiceNumberByType()
        },
        searchNumber(data){
            this.form.name = (this.form.identity_document_type_id === '1')?data.nombre_completo:data.nombre_o_razon_social;
            this.form.trade_name = (this.form.identity_document_type_id === '6')?data.nombre_o_razon_social:'';
            this.form.date_birth = data.fecha_nacimiento;
            this.form.location_id = data.ubigeo;
            this.form.address = data.direccion;
            this.form.department_id = (data.ubigeo) ? (data.ubigeo[0] != '-' ? data.ubigeo[0] : null) : null;
            this.form.province_id = (data.ubigeo) ? (data.ubigeo[1] != '-' ? data.ubigeo[1] : null) : null;
            this.form.district_id = (data.ubigeo) ? (data.ubigeo[2] != '-' ? data.ubigeo[2] : null) : null;
            this.form.condition = data.condicion;
            this.form.state = data.estado;

            this.filterProvinces()
            this.filterDistricts()
//                this.form.addresses[0].telephone = data.telefono;
        },
        clickRemoveAddress(index){
            this.form.addresses.splice(index, 1);
        }
    }
};
</script>