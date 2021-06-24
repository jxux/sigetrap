<template>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header border-0">
                    <div class="d-flex justify-content-between">
                        <h3 class="card-title">Listado de usuarios</h3>
                        <a class="btn btn-primary btn-sm" @click.prevent="clickCreate()"><i class="fas fa-plus"></i> Nuevo</a>                       
                    </div>
                </div>
                <div class="card-body">
                    <data-table :resource="resource">
                        <tr slot="heading">
                            <th>#</th>
                            <th>Nick</th>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Dni</th>
                            <th>Acciones</th>
                        </tr>
                        <tr></tr>
                        <tr slot-scope="{ index, row }" :class="{ disable_color: !row.enabled }">
                            <td>{{ index }}</td>
                            <td>{{ row.nick_name }}</td>
                            <td>{{ row.name }}</td>
                            <td>{{ row.email }}</td>
                            <td>{{ row.dni }}</td>
                            <td>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-info"  @click="clickCreate(row.id)"><i class="far fa-edit"></i></button>
                                    <button type="button" class="btn btn-sm btn-danger" @click.prevent="clickDelete(row.id)"><i class="far fa-trash-alt"></i></button>
                                </div>
                            </td>
                        </tr>
                    </data-table>
                </div>
            </div>
        </div>
        <persons-form :showDialog.sync="showDialog" :recordId="recordId"></persons-form>
    </div>
</template>

<script>
import PersonsForm from "./form.vue";
import { deletable } from "../../../mixins/deletable";
import DataTable from "../../../components/DataTable.vue";
export default {
    mixins: [deletable],
    components: { PersonsForm, DataTable },
    data() {
        return {
		    showDialog: false,
            recordId: null,
            items: [],
            resource: "users",
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
        clickDisable(id) {
            this.disable(`/${this.resource}/enabled/${0}/${id}`).then(() =>
                this.$eventHub.$emit("reloadData")
            );
        },
        clickEnable(id) {
            this.enable(`/${this.resource}/enabled/${1}/${id}`).then(() =>
                this.$eventHub.$emit("reloadData")
        );
        },
    },
};
</script>