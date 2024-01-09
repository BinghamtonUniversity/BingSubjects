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
    });
});
