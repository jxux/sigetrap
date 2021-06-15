<template>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header border-0">
                    <div class="d-flex justify-content-between">
                        <h3 class="card-title">Listado de Centro de Costos</h3>
                        <a class="btn btn-primary btn-sm" @click.prevent="clickEdit()"><i class="fas fa-plus"></i> Nuevo</a>                       
                    </div>
                </div>
                <div class="card-body">
                    <data-table :resource="resource">
                        <tr slot="heading">
                            <th>#</th>
                            <th>Código</th>
                            <th>Nombre</th>
                            <th>Acciones</th>
                        </tr>
                        <tr></tr>
                        <tr slot-scope="{ index, row }">
                            <td>{{ index }}</td>
                            <td>{{ row.code }}</td>
                            <td>{{ row.name }}</td>
                            <td>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-info"  @click.prevent="clickEdit(row.id)"><i class="far fa-edit"></i></button>
                                    <button type="button" class="btn btn-sm btn-danger" @click.prevent="clickDelete(row.id)"><i class="far fa-trash-alt"></i></button>
                                </div>
                            </td>
                        </tr>
                    </data-table>
                </div>
            </div>
        </div>
        <costs-form :recordId="recordId"></costs-form>
    </div>
</template>

<script>
import CostsForm from "./form.vue";
import { deletable } from "../../../mixins/deletable";
import DataTable from "../../../components/DataTable.vue";
export default {
    mixins: [deletable],
    components: {DataTable, CostsForm },
    data() {
        return {
            recordId: null,
            items: [],
            resource: "costs",
        };
    },
    created() {
        axios.get(`/${this.resource}/records`).then((resindex) => {
        this.items = resindex.data.data;

        });
    },
    methods: {
        clickEdit(recordId = null) {
            this.recordId = recordId;
            this.$nextTick(() => {
                this.$bvModal.show('modal-prevent-closing')
            })
        },
        clickDelete(id){
            this.destroy(`/${this.resource}/${id}`).then(() =>
                this.$eventHub.$emit("reloadData")
            );
        }
    },
};
</script>