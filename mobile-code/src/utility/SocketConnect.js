import * as React from 'react';
import SocketIOClient from 'socket.io-client';
const SOCKET_URL = 'http://43.205.172.105:9000/'
// const SOCKET_URL = 'http://3.137.121.138:3001/'
import NetInfo from '@react-native-community/netinfo';

import { io } from "socket.io-client";

var lastMsg = false;
export default class SocketConnect extends React.Component {
  static isSocketConnected = false;
  static isAppBackground = false;

  static checkConnection(cb) {
    // console.log('checkConnection  ');
    NetInfo.fetch()
      .then((isConnected) => {
        if (isConnected) {
          if (SocketConnect.isSocketConnected) {
            // console.log(
            //   '********************** chat Already connected ********************',
            // );
            if (this.socket.connected == false) {
              this.socket.connect();
            }
            cb(true);
            return;
          } else {
            SocketConnect.connectUser();
            cb(false);
          }
        } else {
          cb(false);
          alert(
            'Oops! It seems that you are not connected to the internet. Please check your internet connection and try again.',
          );
        }
      })
      .catch((error) => {
        console.log('error', error);
      });
  }

  static connectUser() {
    // console.log('********************** connectUser ********************');
    this.socket = SocketIOClient(SOCKET_URL, {
      forceNew: true,
      reconnection: true,
      query: {
        user_id: '',
        device_token: '',
      },
    });
    SocketConnect.isSocketConnected = true;
    this.reConnect();
    // *****************Socket Connect*********************
    this.socket.on('connect', (data) => {
      // EventRegister.emit('socket_connected', true)
      console.log(
        '********************** socket connected ********************',
      );
    });

    // *****************Socket Disconnect*********************
    this.socket.on('disconnect', (data) => {
      SocketConnect.isSocketConnected = false;
      console.log(
        '********************** socket disconnect ********************',
      );
      SocketConnect.checkConnection()
      // EventRegister.emit('socket_connected', false)
      if (SocketConnect.isAppBackground == false) {
        this.reConnect();
      }
    });

    // *************Live Page Events**********

    this.socket.on('deleteMessage', (mess) => {
      console.log('deleteMessage    ', mess);
    });

  }

  static reConnect() {
    this.socket.connect();

  }

  static checkSocketConnected() {
    SocketConnect.isSocketConnected = true
    return this.socket.connected();
  }
  static closeConnection() {
    this.socket.close();
    SocketConnect.isSocketConnected = false
  }

  static disconnectUser() {
    this.socket && this.socket.disconnect();
    SocketConnect.isSocketConnected = false
  }

  //**********For fire socket events  */
  static socketDataEvent(eventName, formData) {
    console.log(
      'eventName, formData  ****************  ********************  ****************  ',
      eventName,
      formData,
    );
    if (SocketConnect.isSocketConnected) {
      // console.log('event sent');
      this.socket.emit(eventName, formData);
    } else {
      SocketConnect.checkConnection(() => {
        this.socket.emit(eventName, formData);
      })
    }

  }
  static socketDataEvent1(eventName, formData) {
    console.log(
      'eventName, formData  ****************  ********************  ****************  ',
      eventName,
      formData,
    );
    if (SocketConnect.isSocketConnected) {
      // console.log('event sent');
      this.socket.on("receivedMessage", msg => {
        console.log('=====msg ', msg);
      });
    } else {
      SocketConnect.checkConnection(() => {
        this.socket.on(eventName, formData);
      })
    }

  }

  static socketReconnectDisconnect(eventName) {
    // NetInfo.fetch()
    //   .then((isConnected) => {
    //     if (isConnected) {
    if (eventName == 'connect') {
      if (this.socket != null) {
        this.socket.connect();
        console.log('Old connected');
      } else {
        SocketConnect.checkConnection('');
        console.log('New connected');
      }
    } else {
      if (this.socket != null) {
        this.socket.disconnect();
        console.log('disconnect--------');
      }
    }
    //     } else {
    //       alert(
    //         'Oops! It seems that you are not connected to the internet. Please check your internet connection and try again.',
    //       );
    //     }
    //   })
    //   .catch((error) => {});
  }
}


// // let  socket  = io.connect('https://emit.ae/');

// // socket.on('connect', function() {
// //     console.log(socket.io.engine.id);     // old ID
// //     socket.io.engine.id = 'new ID';
// //     console.log(socket.io.engine.id);     // new ID
// // });