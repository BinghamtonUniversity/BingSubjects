// ajax.get('/api/studies/'+id+'/users',function(data) {
//     console.log(data); // Remove PI from manageable list?
//     data = data.reverse();
//     gdg = new GrapheneDataGrid(
//         {el:'#adminDataGrid',
//             name:'study_users',
//             search:false,columns:false,upload:false,download:false,title:'study_users',
//             entries:[],
//             sortBy:'type',
//             actions:actions,
//             count:20,
//             schema:[
//                 {name:'id',type:'hidden'},
//                 {
//                     name:'type',
//                     type:'select',
//                     label:'Type',
//                     options: [
//                         {
//                             label:"Manager",
//                             value:"manager",
//                         },
//                         {
//                             label:"Viewer",
//                             value:"viewer",
//                         },
//                     ]
//                 },
//                 {
//                     name:"user_id",
//                     type:"text",
//                     label:"User",
//                     template:"{{attributes.first_name}} {{attributes.last_name}} - {{attributes.email}}",
//                 }
//             ],
//             data:data
//         }).on("edit",function(e) {
//             e.preventDefault();
//         }).on("model:edit",function(grid_event) {
//             new gform({
//                 "legend":"Update "+grid_event.model.attributes.first_name+" "+grid_event.model.attributes.last_name+"'s Permission Type For This Study",
//                 "name":"update_study_user_type",
//                 "fields": [
//                     {name:'id',type:'hidden'},
//                     {
//                         name:'type',
//                         type:'select',
//                         label:'Type',
//                         options: [
//                             {
//                                 label:"Manager",
//                                 value:"manager",
//                             },
//                             {
//                                 label:"Viewer",
//                                 value:"viewer",
//                             },
//                         ]
//                     }
//                 ]
//             }).on('save',function(form_event) {
//                 if(form_event.form.validate()) {
//                     form_event.form.trigger('close');
//                     grid_event.model.attributes = form_event.form.get();
//                     ajax.put('/api/studies/'+id+'/users/'+grid_event.model.attributes.id,grid_event.model.attributes,function(data) {
//                         grid_event.model.update(data);
//                     },function(data) {
//                         grid_event.model.undo();
//                     });
//                 }
//             }).on('cancel',function(form_event) {
//                 form_event.form.trigger('close');
//             }).modal().set(grid_event.model.attributes);
//         }).on("create",function(grid_event) {
//             grid_event.preventDefault();
//             new gform({
//                 "legend" : "Add User to Study",
//                 "fields": [
//                     {name:'id',type:'hidden'},
//                     {
//                         name:'type',
//                         type:'select',
//                         label:'Type',
//                         options: [
//                             {
//                                 label:"Manager",
//                                 value:"manager",
//                             },
//                             {
//                                 label:"Viewer",
//                                 value:"viewer",
//                             },
//                         ]
//                     },
//                     {
//                         name:"user_id",
//                         type:"user",
//                         label:"User",
//                         template:"{{attributes.first_name}} {{attributes.last_name}} - {{attributes.email}}",
//                         options:"/api/users",
//                         format: {
//                             label:"{{first_name}} {{last_name}}",
//                             value:"{{id}}",
//                             display:"{{first_name}} {{last_name}}" +
//                                 '<div style="color:#aaa">{{email}}</div>'
//                         }
//                     }
//                 ]
//             }).on('save',function(form_event) {
//                 if(form_event.form.validate()) {
//                     form_data = form_event.form.get();
//                     form_event.form.trigger('close');
//                     ajax.post('/api/studies/'+id+'/users/'+form_data.user_id,form_data,function(data) {
//                         //refresh page
//                         //grid_event.model.update(data);
//                     });
//                 }
//             }).on('cancel',function(form_event) {
//                 form_event.form.trigger('close');
//             }).modal();
//         // }).on("model:created",function(grid_event) {
//         //     ajax.post('/api/studies/'+id+'/users/'+grid_event.model.attributes.user_id,grid_event.model.attributes,function(data) {},function(data) {
//         //         grid_event.model.update(data);
//         //     },function(data) {
//         //         grid_event.model.undo();
//         //     });
//         }).on("model:deleted",function(grid_event) {
//             ajax.delete('/api/studies/'+id+'/users/'+grid_event.model.attributes.id,{},function(data) {},function(data) {
//                 grid_event.model.undo();
//             });
//         });
// });
