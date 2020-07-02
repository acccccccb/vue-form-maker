<template>
    <div style="padding:20px;">
        <el-form size="small" :inline="true">
            <el-form-item>
                <el-input placeholder="搜索" v-model="page.searchText"></el-input>
            </el-form-item>
            <el-form-item>
                <el-button type="primary" @click="getList(1)">查询</el-button>
            </el-form-item>
        </el-form>
        <el-table v-loading="tableLoading" size="small" :data="table.rows" border>
            <el-table-column prop="id" label="ID" width="50" align="center"></el-table-column>
            <el-table-column prop="nickname" label="创建人" width="80" align="center"></el-table-column>
            <el-table-column prop="name" label="表单名" align="center"></el-table-column>
            <el-table-column prop="description" label="表单描述" align="center"></el-table-column>
            <el-table-column prop="jumpUrl" label="跳转链接" align="center"></el-table-column>
            <el-table-column prop="createTime" width="200" label="创建时间" align="center"></el-table-column>
            <el-table-column label="状态" width="100" align="center">
                <template slot-scope="scope">
                    <el-tag size="mini" type="success" v-if="scope.row.enable=='true'">已启用</el-tag>
                    <el-tag size="mini" type="info" v-else>已禁用</el-tag>
                </template>
            </el-table-column>
            <el-table-column align="center" width="250" label="操作">
                <template slot-scope="scope">
                    <el-button size="small" type="text" @click="editForm(scope.row.id)">编辑</el-button>
                    <el-button size="small" type="text" @click="viewForm(scope.row.id)">查看</el-button>
                    <el-button size="small" type="text" @click="clear(scope.row.id)">清空数据</el-button>
                    <el-button size="small" type="text" @click="viewFormData(scope.row.id)">数据</el-button>
                    <el-button size="small" type="text" @click="del(scope.row.id)">删除</el-button>
                </template>
            </el-table-column>
        </el-table>
        <div style="margin-top:20px;">
            <el-pagination
                    background
                    :page-sizes="[10,20,30,50,100]"
                    @size-change="getListSizeChange"
                    @current-change="getList"
                    layout="prev, pager, next,sizes,total"
                    :total="table.total">
            </el-pagination>
        </div>

        <el-dialog
                title="查看表单数据"
                :visible.sync="formDataModal.visible"
                width="80%"
                append-to-body
                :before-close="formDataModalClose">
            <div>
                <div class="el-table el-table--fit el-table--border el-table--enable-row-hover el-table--enable-row-transition el-table--mini">
                    <table class="el-table__body" border="0" style="width:100%;">
                        <tr class="el-table__row">
                            <th class="is-center" width="50">
                                <div class="cell">#</div>
                            </th>
                            <th class="is-center" v-for="item in formDataModal.data.rows[0]">
                                <div class="cell">{{item.fieldLabel}}</div>
                            </th>
                            <th  class="is-center">
                                <div class="cell">提交时间</div>
                            </th>
                        </tr>
                        <tr class="el-table__row" v-for="(item,index) in formDataModal.data.rows">
                            <td class="is-center">
                                <div class="cell">{{index+1}}</div>
                            </td>
                            <td class="is-center" v-for="childItem in item">
                                <div class="cell">{{childItem.fieldValue}}</div>
                            </td>
                            <td class="is-center">
                                <div class="cell">{{item[0].createTime}}</div>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <span slot="footer" class="dialog-footer">
            <el-button type="danger" @click="clear">清空数据</el-button>
            <el-button type="primary" @click="formDataModalClose">关闭</el-button>
          </span>
        </el-dialog>
        <!--viewModal-->
        <el-dialog
                title="查看表单"
                :visible.sync="viewModal.visible"
                width="1200px"
                append-to-body
                :before-close="viewModalClose">
            <div style="min-height:300px;">
                <FormMakerPublish ref="formMakerPublish"></FormMakerPublish>
            </div>
        </el-dialog>
    </div>
</template>

<script>
    import FormMakerPublish from './FormMakerPublish'
    import $api from '../../../api/FormMaker/FormMaker'
    export default {
        name: "FormMakerList",
        components:{
            FormMakerPublish,
        },
        data(){
            return {
                id:null,
                tableLoading:true,
                page:{
                    pageNumber:1,
                    pageSize:20,
                    searchText:'',
                },
                table:{
                    rows:[]
                },
                formDataModal:{
                    visible:false,
                    data:{
                        rows:[],
                        formTabRow:{}
                    }
                },
                viewModal:{
                    visible:false,
                }
            }
        },
        created(){
            this.getList();
        },
        methods:{
            getList:function(currentPage){
                this.tableLoading = true;
                if(currentPage && typeof currentPage == 'number') {
                    this.page.pageNumber = currentPage;
                }
                $api.getList(this.page).then((res)=>{
                    if(res.success==true) {
                        this.table = res;
                    }
                    this.tableLoading = false;
                })
            },
            getListSizeChange:function(pageSize){
                this.page.pageSize = pageSize;
                this.getList();
            },
            viewForm:function(id){
                this.$router.push({name: 'FormMakerPublish',target:'_blank',query:{id:id}});
                // window.open(`/#/FormMakerPublish?id=${id}`);
                // this.viewModal.visible = true;
                // this.$nextTick(()=>{
                //     this.$refs.formMakerPublish.getById(id);
                // });
            },
            editForm:function(id){
                this.$router.push({name: 'FormMaker',target:'_self',params:{id:id}});
            },
            clear:function(id){
                if(id && typeof id == Number) {
                    this.id = id;
                }
                this.$confirm('确定要清空数据吗，该操作无法撤销？','提示',{
                        type:'warning',
                        confirmButtonText:'确定',
                        cancelButtonText:'取消',
                      }).then(()=>{
                    let loading = this.$loading({
                        body:true,
                        fullscreen:true,
                        lock:true,
                        text:'正在清除数据',
                        background:'rgba(255,255,255,0.4)'
                    });
                    $api.clear(this.id).then((res)=>{
                        if(res.success==true) {
                            this.$message.success(res.msg);
                            if(this.formDataModal.visible) {
                                this.viewFormData();
                            }
                        } else {
                            this.$message.warning(res.msg);
                        }
                        loading.close();
                    }).catch(()=>{
                        loading.close();
                    });
                      }).catch(()=>{});
            },
            viewModalClose:function(){
                this.viewModal.visible = false;
            },
            viewFormData:function(id){
                let loading = this.$loading({
                    body:true,
                    fullscreen:true,
                    lock:true,
                    text:'loading',
                    background:'rgba(255,255,255,0.4)'
                });
                this.id = id;
                $api.getDataListByFormId(id).then((res)=>{
                    this.formDataModal.data = res;
                    this.formDataModal.visible = true;
                    loading.close();
                });
            },
            del:function(id){
                this.$confirm('确定删除吗，删除表单后所有收集到的数据都将被删除？','提示',{
                        type:'warning',
                        confirmButtonText:'确定',
                        cancelButtonText:'取消',
                      }).then(()=>{
                    let loading = this.$loading({
                        body:true,
                        fullscreen:true,
                        lock:true,
                        text:'正在删除',
                        background:'rgba(255,255,255,0.4)'
                    });
                    $api.del(id).then((res)=>{
                        if(res.success==true) {
                            this.getList();
                            this.$message.success(res.msg);
                        } else {
                            this.$message.warning(res.msg);
                        }
                        loading.close();
                    });
                      }).catch(()=>{});
            },
            formDataModalClose:function(){
                this.formDataModal.visible = false;
                this.id = null;
            },

        },
    }
</script>

<style scoped>

</style>