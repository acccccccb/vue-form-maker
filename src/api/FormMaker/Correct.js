export default {
    do:function(obj){
        let keys = Object.keys(obj);
        let fields = [];
        keys.forEach((item)=>{
            let data = obj[item];
            data.required = data.required==='true'?true:false;
            data.private = data.private==='true'?true:false;
            if(data.type == "single_line_number") {
                data.step = Number(data.step);
                data.max = Number(data.max);
                data.min = Number(data.min);
                data.precision = Number(data.precision);
                data.value = Number(data.value);
            }
            if(data.type=="single_line_rate") {
                data.value = Number(data.value);
            }
            fields.push(obj[item]);
        });
        return fields;
    },
}