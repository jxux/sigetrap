<template>
    <div>
        <div class="col-md-12">
            <div class="table-responsive">
                <table class="table table-sm table-hover">
                    <thead>
                        <slot name="heading"/>
                    </thead>
                    <tbody>
                        <slot v-for="(row, index) in records" :row="row" :index="customIndex(index)"/>
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
        // let column_resource = _.split(this.resource, "/");
        // await this.$http
        //     .get(`/${_.head(column_resource)}/columns`)
        //     .then(response => {
        //         this.columns = response.data;
        //         this.search.column = _.head(Object.keys(this.columns));
        //     });
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
