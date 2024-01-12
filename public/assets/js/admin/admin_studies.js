ajax.get('/api/studies',function(data) {
    data = data.reverse();
    gdg = new GrapheneDataGrid(
        {el:'#adminDataGrid',
    name: 'studies',
    search: false,columns: false,upload:false,download:false,title:'studies',
    entries:[],
    sortBy: 'order',
    actions:actions,
    count:20,
    schema:[
        {name:"id",type:"hidden"},
        {
            "name": "pi_user_id",
            "label": "PI User",
            "type":"user",
            template:"{{attributes.pi.first_name}} {{attributes.pi.last_name}}"
        },
        {
            "name": "title",
            "label": "Title",
            "type":"text",
        },
        {
            "name": "location",
            "label": "Location",
            "type":"text",
        },
        {
            "name": "description",
            "label": "Description",
            "type":"text",
        },
        {
            "name": "start_date",
            "label": "Start Date",
            "type":"date",
        },
        {
            "name": "end_date",
            "label": "End Date",
            "type":"date",
        }

    ],
    data: data
    }).on("model:edited",function(grid_event) {
        ajax.put('/api/studies/'+grid_event.model.attributes.id,grid_event.model.attributes,function(data) {
            grid_event.model.update(data)
        },function(data) {
            grid_event.model.undo();
        });
    }).on("model:created",function(grid_event) {
        ajax.post('/api/studies',grid_event.model.attributes,function(data) {
            grid_event.model.update(data)
        },function(data) {
            grid_event.model.undo();
        });
    }).on("model:deleted",function(grid_event) {
        ajax.delete('/api/studies/'+grid_event.model.attributes.id,{},function(data) {},function(data) {
            grid_event.model.undo();
        });
    }).on('model:study_participants',function(grid_event){
        window.location = '/studies/'+grid_event.model.attributes.id+'/participants';
        // console.log(grid_event.model.attributes.id)
    });
});
