ajax.get('/api/data_types/'+id+'/studies',function(data) {
    data = data.reverse();
    gdg = new GrapheneDataGrid(
        {el:'#adminDataGrid',
            name:'data_type_studies',
            search:false,columns:false,upload:false,download:false,title:'data_type_studies',
            entries:[],
            sortBy:'order',
            actions:actions,
            count:20,
            schema:[
                {name:"id",type:"hidden"},
                {
                    name:"study_id",
                    type:"combobox",
                    label:"Study",
                    template:"{{attributes.study.title}}, PI: {{attributes.study.pi.first_name}} {{attributes.study.pi.last_name}}",
                    options:"/api/studies",
                    format:{
                        label:"{{title}}, PI: {{pi.first_name}} {{pi.last_name}}",
                        value:"{{id}}",
                        display:"{{title}}" +
                            '<div style="color:#aaa">PI: {{pi.first_name}} {{pi.last_name}}</div>' +
                            '<div style="color:#aaa">Description: {{description}}</div>'+
                            '<div style="color:#aaa">Location: {{location}}</div>'+
                            '<div style="color:#aaa">Dates: {{start_date}} - {{end_date}}</div>'
                    }
                }
            ],
            data:data
        }).on("model:created",function(grid_event) {
        ajax.post('/api/data_types/'+id+'/studies/'+grid_event.model.attributes.study_id, {},function(data) {
            grid_event.model.update(data)
        },function(data) {
            grid_event.model.undo();
        });
    }).on("model:deleted",function(grid_event) {
        ajax.delete('/api/data_types/'+id+'/studies/'+grid_event.model.attributes.study_id,{},function(data) {},function(data) {
            grid_event.model.undo();
        });
    });
});
