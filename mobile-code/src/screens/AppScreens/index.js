// ar app = require('express')();

// var fs = require( 'fs' );

// var http = require('http').createServer(app);

// var server = http.listen(5000, () => {
//     console.log('listening on *:5000');
// });


// var io = require('socket.io')(server);
// // var io = require('socket.io')(server,{
// //   allowEIO3: false
// // });
// const axios = require('axios');
// //
// app.get('/', (req, res) => {
//     res.sendFile(__dirname + '/socket.html');
// });

// // LIVE SetUP CLINTS //////

// const saveMsgUrl = "https://zea-virtual-events.com/shortletrental/api/auth/save-message";
// const seenMsgUrl = "https://zea-virtual-events.com/shortletrental/api/auth/seen-message";

// var clients = {};
// io.on("connection", function (socket) {
//     console.log("socket connected");
//     socket.on('join', function (name) {
//         clients[name] = socket.id;
//         console.log(clients)
//     });

//     socket.on("sendMessage", msg => {
//         console.log("msg sendMessage => ", msg)
//      try{
//         axios.post(saveMsgUrl, msg).then(function (response) {
//                 console.log('api response', response.data);
//                 // Send message to partner
//                // io.to(clients[msg.chatId]).emit('receivedMessage', response.data.data);
//                let otherId = msg.receiver_id.toString() + msg.sender_id.toString();
//                 console.log(msg.chatId, '---------msg.chatId----------');
//                 io.to(clients[otherId]).emit('receivedMessage', response.data.data);
//                 // Receive api response on own side also
//                 let myId = msg.sender_id.toString() + msg.receiver_id.toString();
//                 // let myId = msg.chatId;
//                 console.log('------------myId-------------', myId);
//                 io.to(clients[myId]).emit('sendedMessageDetail', response.data.data);
//             })
//             .catch(function (error) {
//                 console.log(error);
//             });
//         }catch (error) {
//             console.error(error.response.data);     // NOTE - use "error.response.data` (not "error")
//           }
//     });

//     socket.on("message_seen", msg => {
//         console.log("msg message_seen => ", msg)
//     });

//     socket.on("disconnect", function () {
//         console.log("user disconnected");
//     });
// });
