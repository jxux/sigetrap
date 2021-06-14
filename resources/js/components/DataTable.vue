<template>
    <div>
        <div class="row">
            <div class="col-md-12 col-lg-12 col-xl-12 ">
                <b-row v-if="applyFilter">
                    <b-col>
                        <b-form-group id="fieldset-horizontal" label-cols-sm="4" label-cols-lg="3" content-cols-sm content-cols-lg="7" label="Filtrar por: " label-for="input-horizontal">
                            <b-form-select id="input-horizontal" v-model="search.column" :options="columns" placeholder="Select" value-field="name" text-field="name" @change="changeClearInput" size="sm"></b-form-select>
                        </b-form-group>
                    </b-col>
                    <b-col>
                        <template v-if="search.column == 'date_of_issue' || search.column == 'date_of_due' || search.column == 'date_of_payment' || search.column == 'delivery_date'">
                            <!-- <el-date-picker
                                v-model="search.value"
                                type="date"
                                style="width: 100%;"
                                placeholder="Buscar"
                                value-format="yyyy-MM-dd"
                                @change="getRecords">
                            </el-date-picker> -->
                        </template>
                        <template v-else>
                            <b-form-input size="sm" v-model="search.value" @input="getRecords" placeholder="Buscar"></b-form-input>
                        </template>
                    </b-col>
                </b-row>
            </div>

            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <slot name="heading"></slot>
                        </thead>
                        <tbody>
                            <slot v-for="(row, index) in records" :row="row" :index="customIndex(index)"></slot>
                        </tbody>
                    </table>
                    <div>
                        <b-pagination class="mt-5"
                            pills 
                            align="right"
                            @change="getRecords"
                            v-model="pagination.current_page"
                            :total-rows="pagination.total"
                            :per-page="pagination.per_page"></b-pagination>
                            <!-- <b-pagination :link-gen="linkGen" :number-of-pages="pagination.per_page" use-router></b-pagination> -->

                        <!-- <el-pagination
                            @current-change="getRecords"
                            layout="total, prev, pager, next"
                            :total="pagination.total"
                            :current-page.sync="pagination.current_page"
                            :page-size="pagination.per_page"
                        >
                        </el-pagination> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import queryString from "query-string";

export default {
    props: {
        productType: {
            type: String,
            required: false,
            default: ''
        },
        resource: String,
        applyFilter: {
            type: Boolean,
            default: true,
            required: false
        }
    },
    data() {
        return {
            search: {
                column: null,
                value: null
            },
            currentPage: 2,
            columns: [],
            records: [],
            pagination: {},
            loading_submit: false,
        };
    },
    created() {
        this.$eventHub.$on("reloadData", () => {
            this.getRecords();
        });
    },
    async mounted() {
        let column_resource = _.split(this.resource, "/");
        await this.$http
            .get(`/${_.head(column_resource)}/columns`)
            .then(response => {
                this.columns = response.data;
                this.search.column = _.head(Object.keys(this.columns));
            });
        await this.getRecords();
    },
    methods: {
        customIndex(index) {
            return (
                this.pagination.per_page * (this.pagination.current_page - 1) + index + 1
            );
        },
        getRecords() {
            this.loading_submit = true;
            return this.$http
                .get(`/${this.resource}/records?${this.getQueryParameters()}`)
                // .get(`/${this.resource}/records?page=2`)
                .then(response => {
                    this.records = response.data.data;
                    this.pagination = response.data.meta;
                    this.pagination.per_page = parseInt(
                        response.data.meta.per_page
                    );
                })
                .catch(error => {})
                .then(() => {
                    this.loading_submit = false;
                });
        },
        getQueryParameters() {
            if (this.productType == 'ZZ') {
                this.search.type = 'ZZ';
            }
            return queryString.stringify({
                page: this.pagination.current_page,
                limit: this.limit,
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
