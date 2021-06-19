<template>
    <div class="card card-primary card-outline">
        <div class="card-header">
            <b-row>
                <b-col>
                    <h3 class="card-title mr-5"><i class="fas fa-edit"></i>Planificación</h3>
                </b-col>
                <b-col cols="3">
                    <b-form-datepicker id="date-input" v-model="search.value" @input="getRecords" locale="es" size="sm"></b-form-datepicker>
                </b-col>
            </b-row>
        </div>
        <b-card no-body v-if="items != ''">
            <b-tabs card fill>
                <b-tab v-for="(item, index) in items" :key="index" :title="item.user.name" active>
                    <b-card-title>Hora de creacción: {{ moment(item.created_at).format('H:mm a') }}</b-card-title>
                    <b-card-text>
                        <pre>{{ item.description }}</pre>
                    </b-card-text>
                </b-tab>
            </b-tabs>
        </b-card>
        <div class="card-body" v-else>
            <p>No se ha registrado ninguna planificación para este día</p>
        </div>
    </div>
</template>
<script>
    import queryString from "query-string";
    export default {
        data() {
            return {
                items: [],
                resource: "home",
                search: {
                    value: moment().format("YYYY-MM-DD")
                },
            };
        },
        created(){
            return this.$http.get(`/${this.resource}/planning?${this.getQueryParameters()}`)
                .then(response => {
                    this.items = response.data.planning;
                }).catch(error => {
                    
                });
        },
        methods: {
            getRecords() {
                return this.$http.get(`/${this.resource}/planning?${this.getQueryParameters()}`)
                    .then(response => {
                        this.items = response.data.planning;
                    }).catch(error => {
                        
                    });
            },
            getQueryParameters() {
                return queryString.stringify({
                    ...this.search
                });
            },
            changeClearInput() {
                this.search.value = "";
                this.getRecords();
            }
        }
    };
</script>