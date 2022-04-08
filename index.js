// var MongoClient = require('mongodb').MongoClient;
// var url = "mongodb://localhost:27017/";


 // MongoClient.connect(url, function(err, db) {
 //     if (err) throw err;
 //     var dbo = db.db("mydb");
 //     var myobj = { name: "Company Inc", address: "Highway 37" };
 //     dbo.collection("customers").insertOne(myobj, function(err, res) {
 //         if (err) throw err;
 //         console.log("1 document inserted");
 //         db.close();
 //     });
 // });
 

// MongoClient.connect(url, function(err, db) {
//     if (err) throw err;
//     var dbo = db.db("mydb");
//     var myobj = [
//         { name: 'John', address: 'Highway 71'},
//         { name: 'Peter', address: 'Lowstreet 4'},
//         { name: 'Amy', address: 'Apple st 652'},
//         { name: 'Hannah', address: 'Mountain 21'},
//         { name: 'Michael', address: 'Valley 345'},
//         { name: 'Sandy', address: 'Ocean blvd 2'},
//         { name: 'Betty', address: 'Green Grass 1'},
//         { name: 'Richard', address: 'Sky st 331'},
//         { name: 'Susan', address: 'One way 98'},
//         { name: 'Vicky', address: 'Yellow Garden 2'},
//         { name: 'Ben', address: 'Park Lane 38'},
//         { name: 'William', address: 'Central st 954'},
//         { name: 'Chuck', address: 'Main Road 989'},
//         { name: 'Viola', address: 'Sideway 1633'}
//     ];
//     dbo.collection("customers").insertMany(myobj, function(err, res) {
//         if (err) throw err;
//         console.log("Number of documents inserted: " + res.insertedCount);
//         db.close();
//     });
// });


// MongoClient.connect(url, function(err, db) {
//     if (err) throw err;
//     var dbo = db.db("mydb");
//     dbo.collection("customers").find({}, { projection: {  _id:0, name: 1, address: 1 } }).toArray(function(err, result)
//     {
//         if (err) throw err;
//         console.log(result);
//         db.close();
//     });
// });


// MongoClient.connect(url, function(err, db) {
//     if (err) throw err;
//     var dbo = db.db("mydb");
//     var query = { address: "Park Lane 38" };
//     dbo.collection("customers").find(query).toArray(function(err, result) {
//         if (err) throw err;
//         console.log(result);
//         db.close();
//     });
// });

// MongoClient.connect(url, function(err, db) {
//     if (err) throw err;
//     var dbo = db.db("mydb");
//     var mysort = { name: 1 };
//     dbo.collection("customers").find().sort(mysort).toArray(function(err, result) {
//         if (err) throw err;
//         console.log(result);
//         db.close();
//     });
// });



var MongoClient = require('mongodb').MongoClient;
var url = "mongodb://127.0.0.1:27017/";

MongoClient.connect(url, function(err, db) {
    if (err) throw err;
    var dbo = db.db("wh");
    dbo.collection('pv').aggregate([
        { $lookup:
                {
                    from: 'spr_group',
                    localField: 'group_pv',
                    foreignField: 'id',
                    as: 'orderdetails'
                }
        }
    ]).toArray(function(err, res) {
        if (err) throw err;
        console.log(JSON.stringify(res));
        db.close();
    });
});
