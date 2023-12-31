ajax.get('/api/participants',function(data) {
    data = data.reverse();
    gdg = new GrapheneDataGrid({el:'#adminDataGrid',
    name: 'groups',
    search: false,columns: false,upload:false,download:false,title:'Groups',
    entries:[],
    sortBy: 'order',
    actions:actions,
    count:20,
    schema:[
        {name:"id",type:"hidden"},
        {
            "name": "first_name",
            "label": "First Name",
            "type":"text",
        },
        {
            "name": "last_name",
            "label": "Last Name",
            "type":"text",
        },
        {
            "name": "date_of_birth",
            "label": "Date of Birth",
            "type": "date",
        },
        {
            "name": "sex",
            "label": "Sex",
            "type": "select",
            "options": [
                {
                    "label": "Male",
                    "value": "male"
                },
                {
                    "label": "Female",
                    "value": "female"
                }
            ],
        },
        {
            "name": "race",
            "label": "Race",
            "type": "select",
            "options": [
                {
                    "label": "American Indian",
                    "value": "american_indian"
                },
                {
                    "label": "Asian",
                    "value": "asian"
                },
                {
                    "label": "Black or African American",
                    "value": "black"
                },
                {
                    "label": "Native Hawaiian or Other Pacific Islander",
                    "value": "pacific_islander"
                },
                {
                    "label": "White",
                    "value": "white"
                }
            ],
        },
        {
            "name": "city_of_birth",
            "label": "City of Birth",
        },
        {
            "name": "email",
            "label": "Email",
        },
        {
            "name": "phone_number",
            "label": "Phone Number",
        }

    ], 
    data: data
    }).on("model:edited",function(grid_event) {
        ajax.put('/api/participants/'+grid_event.model.attributes.id,grid_event.model.attributes,function(data) {
            grid_event.model.update(data)
        },function(data) {
            grid_event.model.undo();
        });
    }).on("model:created",function(grid_event) {
        ajax.post('/api/participants',grid_event.model.attributes,function(data) {
            grid_event.model.update(data)
        },function(data) {
            grid_event.model.undo();
        });
    }).on("model:deleted",function(grid_event) {
        ajax.delete('/api/participants/'+grid_event.model.attributes.id,{},function(data) {},function(data) {
            grid_event.model.undo();
        });
    });
});