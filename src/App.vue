<template>
    <div id="app">
        <el-container style="height:100%;">
            <el-header v-show="true" class="formMakerHeader">
                <el-menu
                        background-color="#303133"
                        text-color="#fff"
                        active-text-color="#fff"
                        :router="true"
                        default-active="/"
                        mode="horizontal">
                    <el-menu-item>
                        <div class="formMakerLogo">
                            FormMaker
                            <el-tag type="danger" effect="dark" size="mini">{{config.version}}</el-tag>
                        </div>
                    </el-menu-item>
                    <el-menu-item index="/" active>创建表单</el-menu-item>
                    <el-menu-item index="/FormMakerList">表单列表</el-menu-item>
                </el-menu>
            </el-header>
            <el-main style="padding:0;">
                <router-view></router-view>
            </el-main>
            <el-footer class="formMakerFooter">
                <a class="formMakerFooterLink" :href="config.homepage" target="_blank">{{config.name}}-{{config.version}}</a>
                code by <a class="formMakerFooterLink" :href="config.homepage" target="_blank">{{config.author}}</a>
            </el-footer>
        </el-container>
    </div>
</template>

<script>
    import config from '../package'

    export default {
        name: 'App',
        data() {
            return {
                config: {}
            }
        },
        mounted() {
            this.config = config;
        },
    }
</script>

<style>
    html, body, #app {
        height: 100%;
        overflow-x: hidden;
        margin: 0;
        font-size: 14px;
    }

    .clearfix {
        clear: both;
    }
    .formMakerHeader {
        background:#303133;
        color:#fff;
        height:60px;
        line-height:60px;
    }
    .formMakerLogo {
        font-size:1.8em;
    }
    .formMakerFooter {
        height:30px!important;
        line-height:30px;
        font-size:10px;
        text-align:center;
        background:#303133;
        color:#fff;
    }
    .formMakerFooterLink {
        color:#fff;
        text-decoration: none;
    }
</style>
