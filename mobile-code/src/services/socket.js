import socketio from 'socket.io-client'
// https://emit.ae//

// var socket=socketio("https://emit.ae/")


 const socket = socketio.connect("wss://emit.ae/",{
    reconnectionDelay: 1000,
    reconnection: true,
    reconnectionAttempts: Infinity,
    jsonp: false
  });
// const receivedMessage = socket.on('receivedMessage', function (msg) {
// return msg;
// })

  export {socket} ;