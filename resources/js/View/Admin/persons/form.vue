<template>
    <b-modal v-model="showDialog" title="Nuevo cliente" size="lg">

    <b-form @submit.prevent="submit">
        <b-container>
            <b-row>
                <b-col cols="2">
                    <b-form-group label="Codigo: ">
                        <b-form-input size="sm" v-model="form.internal_code" placeholder="Ingrese codigo" required></b-form-input>
                    </b-form-group>
                </b-col>
                <b-col>
                    <b-form-group label="Tipo Doc. Identidad: ">
                        <b-form-select v-model="form.identity_document_type_id" :options="identity_document_types" value-field="id" text-field="description" size="sm" required></b-form-select>
                    </b-form-group>
                </b-col>
                <b-col>
                    <b-form-group label="Número: ">
                        <x-input-service :identity_document_type_id="form.identity_document_type_id" v-model="form.number" @search="searchNumber"></x-input-service>
                    </b-form-group>
                </b-col>
            </b-row>
        </b-container>
        
        <b-container>
            <b-row>
                <b-col>
                    <b-form-group label="Nombre: ">
                        <b-form-input size="sm" v-model="form.name" required></b-form-input>
                    </b-form-group>
                </b-col>
                <b-col>
                    <b-form-group label="Nombre comercial: ">
                        <b-form-input size="sm" v-model="form.trade_name"></b-form-input>
                    </b-form-group>
                </b-col>
            </b-row>
        </b-container>

        <b-container>
            <b-row>
                <b-col>
                    <b-form-group label="País: ">
                        <b-form-select v-model="form.country_id" :options="countries" value-field="id" text-field="description" size="sm" required></b-form-select>
                    </b-form-group>
                </b-col>
                <b-col>
                    <b-form-group label="Departamento: ">
                        <b-form-select v-model="form.department_id" :options="all_departments" @change="filterProvince" value-field="id" text-field="description" size="sm"></b-form-select>
                    </b-form-group>
                </b-col>
                <b-col>
                    <b-form-group label="Provincia: ">
                        <b-form-select v-model="form.province_id" :options="all_provinces" @change="filterDistrict" value-field="id" text-field="description" size="sm"></b-form-select>
                    </b-form-group>
                </b-col>

            </b-row>
        </b-container>

        <b-container>
            <b-row>
                <b-col cols="4">
                    <b-form-group label="Distrito: ">
                        <b-form-select v-model="form.district_id" :options="all_districts" value-field="id" text-field="description" size="sm"></b-form-select>
                    </b-form-group>
                </b-col>
                <b-col cols="8">
                    <b-form-group label="Dirección: ">
                        <b-form-input size="sm" v-model="form.address"></b-form-input>
                    </b-form-group>
                </b-col>
            </b-row>
        </b-container>
        
        <b-container>
            <b-row>
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
    </b-form>

    <template #modal-footer>
        <div class="w-100">
            <b-button
                variant="dark"
                size="sm"
                class="float-left"
                @close="close">Cancelar
            </b-button>
            <b-button
                variant="primary"
                size="sm"
                class="float-right"
                @click="showDialog=false">Guardar
            </b-button>
        </div>
    </template>

  </b-modal>
</template>

<script>
import {serviceNumber} from '../../../mixins/functions'
export default {
    mixins: [serviceNumber],
    props: ["showDialog", "recordId", 'input_person'],
    data() {
        return {
            resource: "persons",
            name: "",
            form: {},
            nameState: null,
            submittedNames: [],
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
        await this.$http.get(`/${this.resource}/tables`)
            .then((resindex) => {
                this.countries = resindex.data.countries;
                this.all_departments = resindex.data.departments;
                this.all_provinces = resindex.data.provinces;
                this.all_districts = resindex.data.districts;
                this.identity_document_types = resindex.data.identity_document_types;
                this.locations = resindex.data.locations;
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
                type: this.type,
                identity_document_type_id: '6',
                number: '',
                name: null,
                trade_name: null,
                country_id: 'PE',
                department_id: null,
                province_id: null,
                district_id: null,
                address: null,
                telephone: null,
                condition: null,
                state: null,
                email: null,
                perception_agent: false,
                percentage_perception:0,
                person_type_id:null,
                comment:null,
                addresses: [],
                contact: {
                    full_name: null,
                    phone: null,
                },
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
            if(this.type === 'customers') {
                this.titleDialog = (this.recordId)? 'Editar Cliente':'Nuevo Cliente'
            }
            if(this.type === 'suppliers') {
                this.titleDialog = (this.recordId)? 'Editar Proveedor':'Nuevo Proveedor'
            }
            if (this.recordId) {
                this.$http.get(`/${this.resource}/record/${this.recordId}`)
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
                return this.$message.error(val_digits.message)
            }

            this.loading_submit = true

            await this.$http.post(`/${this.resource}`, this.form)
                .then(response => {
                    if (response.data.success) {
                        this.$message.success(response.data.message)
                        if (this.external) {
                            this.$eventHub.$emit('reloadDataPersons', response.data.id)
                        } else {
                            this.$eventHub.$emit('reloadData')
                        }
                        this.close()
                    } else {
                        this.$message.error(response.data.message)
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