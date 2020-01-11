<template>
    <el-container style="height:100%;overflow:hidden;">
        <el-aside width="300px" style="background:#fff;overflow-x:hidden;">
            <div style="padding:20px;">
                <div style="margin-bottom:10px;"><strong>数据</strong></div>
                <div>
                    <el-button @click="viewModalOpen" type="success" size="small" style="width:100%;">查看表单列表</el-button>
                    <el-dialog
                            title="表单列表"
                            :visible.sync="viewModal.visible"
                            width="90%"
                            :close-on-click-modal="false"
                            :before-close="viewModalClose">
                        <div>
                            <FormMakerList></FormMakerList>
                        </div>
                        <span slot="footer" class="dialog-footer">
                                <el-button type="primary" @click="viewModalClose">关闭</el-button>
                              </span>
                    </el-dialog>
                </div>
            </div>
            <FormControl v-on:add="addFormControl"></FormControl>
            <div style="padding:20px;">
                <div style="margin-bottom:10px;"><strong>大纲</strong></div>
                <div style="border:1px solid #dedede;">
                    <el-tree
                            v-if="refreshLeftTree"
                            :highlight-current="true"
                            @node-click="formControlClick"
                            @node-drop="nodeDropSuccess"
                            :data="form.fields"
                            node-key="name"
                            draggable
                            :allow-drop="catchAllowDrop"
                            default-expand-all>
                        <div class="custom-tree-node treeNodeList" slot-scope="{ node, data }">
                            {{data.label}}
                        </div>
                    </el-tree>
                </div>
            </div>
        </el-aside>
        <el-main style="background:#fafafa;">
            <div style="padding-bottom:100px;">
                <div class="formBoxDec">
                    <h2>
                        {{form.name||'表单标题'}}
                    </h2>
                    <div>{{form.description||'表单描述'}}</div>
                </div>
                <div class="formBox">
                    <el-form label-position="top" :rules="form.rules" v-model="form.formData">
                        <el-tree
                                ref="createFormTree"
                                v-if="refreshLeftTree"
                                class="form-control-tree"
                                height="200"
                                :highlight-current="true"
                                @node-click="formControlClick"
                                @node-drop="nodeDropSuccess"
                                :data="form.fields"
                                node-key="name"
                                draggable
                                :allow-drop="catchAllowDrop"
                                default-expand-all>
                            <div class="custom-tree-node" slot-scope="{ node, data }">
                                <el-form-item
                                        :show-message="false"
                                        :prop="data.name+''"
                                        class="formItem"
                                        :rules="(data.required)?data.rules:[]"
                                        :label="data.label||'字段名'">
                                    <div class="formItemAction">
                                        <el-button size="mini" @click="moveUp(data)" type="text" icon="el-icon-top"></el-button>
                                        <el-button size="mini" @click="moveDown(data)" type="text" icon="el-icon-bottom"></el-button>
                                        <el-button size="mini" @click="deleteItem(data)" type="text" icon="el-icon-delete"></el-button>
                                    </div>

                                    <SelectChoice v-if="data.type=='select_choice'" :data="data"></SelectChoice>
                                    <SingleChoice v-if="data.type=='single_choice'" :data="data"></SingleChoice>
                                    <MultipleChoice v-if="data.type=='multiple_choice'" :data="data"></MultipleChoice>
                                    <InputText v-if="data.type=='paragraph_text'" :data="data"></InputText>
                                    <InputText v-if="data.type=='single_line_text'" :data="data"></InputText>
                                    <InputText v-if="data.type=='single_line_phone'" :data="data"></InputText>
                                    <InputText v-if="data.type=='single_line_number'" :data="data"></InputText>
                                    <DateTimePicker v-if="data.type=='single_line_date'" :data="data"></DateTimePicker>
                                    <DateTimePicker v-if="data.type=='single_line_time'" :data="data"></DateTimePicker>
                                    <Rate v-if="data.type=='single_line_rate'" :data="data"></Rate>


                                </el-form-item>
                            </div>
                        </el-tree>
                        <el-form-item style="margin-top:20px;">
                            <el-button :style="'width:100%;border:none;background:'+form.submitBtnColor + ';color:'+form.submitBtnTextColor" type="primary">提交</el-button>
                        </el-form-item>
                    </el-form>
                </div>
            </div>
        </el-main>
        <el-aside width="400px" >
            <div style="background:#fff;padding:20px;">
                <el-tabs v-model="activeName">
                    <el-tab-pane label="表单设置" name="first">
                        <el-form size="small" ref="createForm" :model="form">
                            <el-form-item :rules="[
                            { required: true, message: '请填写表单标题', trigger: ['blur','change'] },
                        ]" prop="name" label="表单标题">
                                <el-input v-model="form.name" placeholder="请填写表单标题"></el-input>
                            </el-form-item>
                            <el-form-item prop="description" :rules="[
                            { required: true, message: '请填写表单描述', trigger: ['blur','change'] },
                        ]" label="请填写表单描述">
                                <el-input
                                        type="textarea"
                                        :autosize="{ minRows: 3, maxRows:10}"
                                        v-model="form.description"
                                        :active-value="true"
                                        :inactive-value="false"
                                        placeholder="请填写表单描述">
                                </el-input>
                            </el-form-item>
                            <el-form-item prop="jumpUrl" label="提交表单后跳转URL">
                                <el-input type="text" v-model="form.jumpUrl" placeholder="http://"></el-input>
                            </el-form-item>

                            <el-form-item style="width:49%;display:inline-block;" label="按钮颜色">
                                <el-color-picker size="mini" v-model="form.submitBtnColor"></el-color-picker>
                            </el-form-item>
                            <el-form-item style="width:49%;display:inline-block;" label="按钮文字颜色">
                                <el-color-picker size="mini" v-model="form.submitBtnTextColor"></el-color-picker>
                            </el-form-item>

                            <el-form-item prop="enable">
                                <el-switch
                                        v-model="form.enable"
                                        active-color="#13ce66"
                                        inactive-color="#ff4949"
                                        active-text="启用"
                                        inactive-text="禁用">
                                </el-switch>
                            </el-form-item>
                        </el-form>
                    </el-tab-pane>
                    <el-tab-pane label="字段设置" name="second">
                        <!--编辑区-->
                        <div style="background:#fffceb;padding:20px;">
                            <div  v-if="editor.name">
                                <div><h3>编辑字段：</h3></div>
                                <el-form size="mini">
                                    <el-form-item label="字段名">
                                        <el-input v-model="editor.label" placeholder="字段名"></el-input>
                                    </el-form-item>
                                    <el-form-item label="为空时提示">
                                        <el-input v-model="editor.notes" placeholder="字段名"></el-input>
                                    </el-form-item>
                                    <el-form-item label="是否必填">
                                        <el-radio border v-model="editor.required" :label="true">是</el-radio>
                                        <el-radio border v-model="editor.required" :label="false">否</el-radio>
                                    </el-form-item>

                                    <div v-if="editor.type=='single_line_number'">
                                        <el-form-item label="最小" >
                                            <el-input-number v-model.number="editor.min"></el-input-number>
                                        </el-form-item>
                                        <el-form-item label="最大" >
                                            <el-input-number v-model.number="editor.max" :min.number="editor.min"></el-input-number>
                                        </el-form-item>
                                        <el-form-item label="精度" >
                                            <el-input-number :min="0" v-model.number="editor.precision"></el-input-number>
                                        </el-form-item>
                                        <el-form-item label="步长" >
                                            <el-input-number :min="0" :max="editor.max" :precision.number="editor.precision" v-model.number="editor.step"></el-input-number>
                                        </el-form-item>
                                    </div>

                                    <div v-if="editor.choices">
                                        <div style="margin-bottom:10px;">选项</div>
                                        <el-form-item v-for="(item,index) in editor.choices" :key="index">
                                            <el-input v-model="editor.choices[index].value" placeholder="字段名">
                                                <el-button @click="deleteChoices(index)" slot="append" icon="el-icon-delete"></el-button>
                                            </el-input>
                                        </el-form-item>
                                        <el-form-item>
                                            <el-button @click="addChoices" size="small" type="warning" plain>添加一项</el-button>
                                        </el-form-item>
                                    </div>
                                </el-form>
                            </div>
                            <div style="text-align:center;opacity:0.5;" v-else>
                                <div style="font-size:3em;">
                                    <i class="el-icon-edit"></i>
                                </div>
                                <div >
                                    没有选定的字段 <br>
                                    请添加或者选择一个字段
                                </div>
                            </div>
                        </div>
                    </el-tab-pane>
                </el-tabs>
            </div>
            <div style="padding-left:20px;padding-right:20px;">
                <el-button @click="saveForm" :disabled="form.fields.length==0" style="width:100%;" size="small" type="danger" >保存并发布</el-button>
                <el-button @click="resetForm" style="width:100%;margin-left:0;margin-top:10px;" size="small" type="default" >重置</el-button>
            </div>
        </el-aside>
    </el-container>
</template>

<script>
    import $api from '../../../api/FormMaker/FormMaker'
    import FormMakerList from './FormMakerList'
    import FormControl from './FormMaker/FormControl/FormControl'
    import SelectChoice from './FormMaker/FormControl/FormControlComponents/SelectChoice'
    import SingleChoice from './FormMaker/FormControl/FormControlComponents/SingleChoice'
    import MultipleChoice from './FormMaker/FormControl/FormControlComponents/MultipleChoice'
    import InputText from './FormMaker/FormControl/FormControlComponents/InputText'
    import DateTimePicker from './FormMaker/FormControl/FormControlComponents/DateTimePicker'
    import Rate from './FormMaker/FormControl/FormControlComponents/Rate'
    export default {
        name: "FormMaker",
        components:{
            FormMakerList,
            FormControl,
            SelectChoice,
            SingleChoice,
            MultipleChoice,
            InputText,
            DateTimePicker,
            Rate,
        },
        data(){
            return {
                activeName:'first',
                form:{
                    "name": "",
                    "description": "",
                    "enable":true,
                    "jumpUrl":"",
                    "submitBtnColor":"#409EFF",//提交按钮颜色
                    "submitBtnTextColor":"#fff",//提交按钮文字颜色
                    "fields": [],
                    formData:{},
                },
                activeEditorIndex:null,
                activeEditorKey:null,
                refreshLeftTree:true,
                editor:{},
                viewModal:{
                    visible:false,
                },
            }
        },
        methods:{
            radomNumber:function(){
                return Math.ceil(Math.random()*10000);
            },
            moveUp:function(data){
                let tempData = [...this.form.fields];
                this.form.fields.forEach((item,index)=>{
                    if(item.name==data.name) {
                        if(index>0) {
                            let data1 = tempData[index];
                            let data2 = tempData[index-1];
                            tempData.splice(index-1,2,data1);
                            tempData.splice(index,0,data2);
                            this.$set(this.form,'fields',tempData);
                        }
                    }
                })
            },
            moveDown:function(data){
                let tempData = [...this.form.fields];
                this.form.fields.forEach((item,index)=>{
                    if(item.name==data.name) {
                        if(index<this.form.fields.length-1) {
                            let data1 = tempData[index];
                            let data2 = tempData[index+1];
                            tempData.splice(index,2,data1);
                            tempData.splice(index,0,data2);
                            this.$set(this.form,'fields',tempData);
                        }
                    }
                })
            },
            deleteItem:function(data){
                this.$confirm('确定要删除吗？此操作无法撤回','提示',{
                        type:'warning',
                        confirmButtonText:'确定',
                        cancelButtonText:'取消',
                      }).then(()=>{
                        let node = this.$refs['createFormTree'].getCurrentNode();
                        if(node && node.name==data.name) {
                            this.$refs['createFormTree'].setCurrentKey(null);
                        }
                        this.$refs['createFormTree'].remove(data);
                        delete this.form.formData[data.name];
                        this.editor = {};
                      }).catch(()=>{});
                return false;
            },
            addFormControl:function(controlData){
                if(controlData.value == '0') { controlData.value = 0; }
                let key = this.$refs['createFormTree'].getCurrentKey();
                let currentData = this.form.formData[key];
                if(currentData) {
                    this.$refs['createFormTree'].insertAfter(controlData,node);
                } else {
                    this.form.fields.push(controlData);
                }
                this.form.formData[controlData.name] = controlData.value;
            },
            catchAllowDrop:function(draggingNode, dropNode, type){
                if(type==='prev'||type==='next') {
                    return type;
                }
            },
            formControlClick:function(data,type,childType){
                this.activeEditorKey = data.name;
                this.editor = data;
            },
            addChoices:function(){
                let choices = [...this.editor.choices];
                choices.push({
                    name:'选项'+(this.editor.choices.length+1),
                    value:'选项'+(this.editor.choices.length+1),
                });
                this.editor.choices = choices;
            },
            deleteChoices:function(index){
                this.editor.choices.splice(index,1);
            },
            saveForm:function(){
                let $form = this.$refs['createForm'];
                $form.validate((valid)=>{
                    if(valid) {
                        let loading = this.$loading({
                            body:true,
                            fullscreen:true,
                            lock:true,
                            text:'正在保存表单',
                            background:'rgba(255,255,255,0.4)'
                        });
                        console.log(this.form);
                        $api.add({...this.form}).then((res)=>{
                            if(res.success==true) {
                                this.$message.success(res.msg);
                                this.resetForm(true);
                            } else {
                                this.$message.warning(res.msg);
                            }
                            loading.close();
                        }).catch(()=>{
                            loading.close();
                        });
                    }
                });
            },
            resetForm:function(action){
                let _this = this;
                let reset = function(){
                    _this.$refs.createForm.resetFields();
                    _this.form = {
                        "name": "",
                        "description": "",
                        "enable":true,
                        "jumpUrl":"",
                        "submitBtnColor":"#409EFF",//提交按钮颜色
                        "submitBtnTextColor":"#fff",//提交按钮文字颜色
                        "fields": [],
                        formData:{},
                    };
                    _this.activeEditorIndex=null;
                    _this.activeEditorKey=null;
                    _this.editor={};
                };
                if(action && action===true) {
                    reset();
                } else {
                    this.$confirm('确定重置吗？','提示',{
                        type:'warning',
                        confirmButtonText:'确定',
                        cancelButtonText:'取消',
                    }).then(()=>{
                        reset();
                    }).catch(()=>{});
                }
            },
            nodeDropSuccess:function(before,after,inner){
                this.refreshLeftTree = false;
                this.$nextTick(()=>{
                    this.refreshLeftTree = true;
                })
            },
            viewModalOpen:function(){
                this.viewModal.visible = true;
            },
            viewModalClose:function(){
                this.viewModal.visible = false;
            },
        },
        watch:{
            editor:{//深度监听，可监听到对象、数组的变化
                handler(val, oldVal){
                    this.form.fields.forEach((item,index)=>{
                        if(item.name==val.name) {
                            // this.$set(this.form,'fields['+ index +']',val);
                            this.form.fields[index] = val;
                            this.$forceUpdate();
                        }
                    });
                },
                deep:true
            }
        }
    }
</script>

<style scoped>
    @import "../../../../static/css/FormMaker.scss";
    .treeNodeList {
        overflow:hidden;text-overflow:ellipsis;white-space: nowrap;
        padding-top:20px;
        padding-bottom:20px;
    }
</style>