ajax.get('/api/participants/'+id[0]+'/studies',function(data) {
    data = data.reverse();
    gdg = new GrapheneDataGrid(
        {el:'#adminDataGrid',
            name:'participant_studies',
            search:false,columns:false,upload:false,download:false,title:'participant_studies',
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
                    template:"{{attributes.title}}, PI: {{attributes.pi.first_name}} {{attributes.pi.last_name}}",
                    options:"/api/studies/users/"+id[1],
                    format:{
                        label:"{{title}}, PI: {{pi.first_name}} {{pi.last_name}}",
                        value:"{{id}}",
                        display:"{{title}}" +
                            '<div style="color:#aaa">PI: {{pi.first_name}} {{pi.last_name}}</div>' +
                            '<div style="color:#aaa">Description: {{description}}</div>'+
                            '<div style="color:#aaa">Location: {{location}}</div>'+
                            '<div style="color:#aaa">Dates: {{start_date}} - {{end_date}}</div>'
                    },
                }
            ],
            data:data
        }).on("model:created",function(grid_event) {
            debugger
        ajax.post('/api/participants/'+id[0]+'/studies/'+grid_event.model.attributes.study_id, {},function(data) {
            grid_event.model.update(data)
        },function(data) {
            grid_event.model.undo();
        });
    }).on("model:deleted",function(grid_event) {
        ajax.delete('/api/participants/'+id[0]+'/studies/'+grid_event.model.attributes.id,{},function(data) {},function(data) {
            grid_event.model.undo();
        });
    });
});
