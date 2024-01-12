ajax.get('/api/studies/'+id+'/participants',function(data) {
    data = data.reverse();
    gdg = new GrapheneDataGrid(
        {el:'#adminDataGrid',
            name: 'study_participants',
            search: false,columns: false,upload:false,download:false,title:'study_participants',
            entries:[],
            sortBy: 'order',
            actions:actions,
            count:20,
            schema:[
                {name:"id",type:"hidden"},
                {name:'participant_id', type:'user', label: "Participant", template:"{{attributes.participant.first_name}} {{attributes.participant.last_name}}"}
            ],
            data: data
        }).on("model:created",function(grid_event) {
            // console.log(grid_event.model.attributes)
        ajax.post('/api/studies/'+id+'/participants/'+grid_event.model.attributes.participant_id, {},function(data) {
            grid_event.model.update(data)
        },function(data) {
            grid_event.model.undo();
        });
    }).on("model:deleted",function(grid_event) {
        ajax.delete('/api/studies/'+id+'/participants'+grid_event.model.attributes.participant_id,{},function(data) {},function(data) {
            grid_event.model.undo();
        });
    });
});
