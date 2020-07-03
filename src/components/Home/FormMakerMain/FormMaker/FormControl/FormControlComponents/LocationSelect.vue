<template>
    <div>
        <el-cascader
                filterable
                v-if="data.type=='location_select'"
                :name="data.name"
                :placeholder="data.notes"
                v-model="data.value"
                :options="options"
                :props="props"></el-cascader>
    </div>
</template>
<script>
  import { region } from '../../../../../../../static/utils/region'
  export default {
    name:'LocationSelect',
      props:{
        data:{
            require:true
        }
      },
    data(){
      return {
          options:[],
          props:{
              label: 'name',
              value: 'code',
          }
      }
    },
      created(){
          this.options = region;
      },
    methods:{

    }
  }
</script>
<style scoped>

</style>