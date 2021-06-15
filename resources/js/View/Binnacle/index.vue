<template>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header border-0">
                    <div class="d-flex justify-content-between">
                        <h3 class="card-title">Listado de Eventos</h3>
                        <a class="btn btn-primary btn-sm" @click.prevent="clickCreate()"><i class="fas fa-plus"></i> Nuevo</a>                       
                    </div>
                </div>
                <div class="card-body">
                    <data-table :resource="resource">
                        <tr slot="heading">
                            <th>#</th>
                            <th>Fecha</th>
                            <th>H. Inicio</th>
                            <th>H. Fin</th>
                            <th>Cliente</th>
                            <th>Tiempo</th>
                            <th>Cuenta</th>
                            <th>C. Costo</th>
                            <th>Periodo</th>
                            <th>Descripción</th>
                            <th>Estado</th>
                        </tr>
                        <tr></tr>
                        <tr slot-scope="{ index, row }">
                            <td>{{ index }}</td>
                            <td>{{ row.date }}</td>
                            <td>{{ row.start_time }}</td>
                            <td>{{ row.end_time }}</td>
                            <td>{{ row.client }}</td>
                            <td>{{ row.hour }}</td>
                            <td>{{ row.category }}</td>
                            <td>{{ row.service }}</td>
                            <td>{{ row.period }}</td>
                            <td>{{ row.description }}</td>
                            <td>{{ row.status }}</td>
                            <td>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-info"  @click.prevent="clickCreate(row.id)"><i class="far fa-edit"></i></button>
                                    <button type="button" class="btn btn-sm btn-danger" @click.prevent="clickDelete(row.id)"><i class="far fa-trash-alt"></i></button>
                                </div>
                            </td>
                        </tr>
                    </data-table>
                </div>
            </div>
        </div>
        <binnacles-form :showDialog.sync="showDialog" :recordId="recordId"></binnacles-form>
    </div>
</template>

<script>
import BinnaclesForm from "./form.vue";
import { deletable } from "../../mixins/deletable";
import DataTable from "../../components/DataTable.vue";
export default {
    mixins: [deletable],
    components: { BinnaclesForm, DataTable },
    data() {
        return {
		    showDialog: false,
            recordId: null,
            items: [],
            resource: "binnacles",
        };
    },
    created() {
        axios.get(`/${this.resource}/records`).then((resindex) => {
        this.items = resindex.data.data;
        this.totalRows = resindex.data.meta.total;
        // this.perPage = resindex.data.meta.per_page;
        });
    },
    computed: {
        sortOptions() {
        // Create an options list from our fields
        return this.fields
            .filter((f) => f.sortable)
            .map((f) => {
            return { text: f.label, value: f.key };
            });
        },
    },
    mounted() {
        // Set the initial number of items
        this.totalRows = this.items.length;
    },
    methods: {
        clickCreate(recordId = null) {
            this.recordId = recordId;
            this.$nextTick(() => {
                this.$bvModal.show('modal-prevent-closing')
            })
        },
        clickDelete(id) {
            this.destroy(`/${this.resource}/${id}`).then(() =>
                this.$eventHub.$emit("reloadData")
            );
        },
    },
};
</script>