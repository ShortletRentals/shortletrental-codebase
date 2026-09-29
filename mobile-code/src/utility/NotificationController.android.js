import React, { useEffect } from 'react';
import { Alert, AppState } from 'react-native';
import messaging from '@react-native-firebase/messaging';
import PushNotification from 'react-native-push-notification';
// import PushNotificationIos from '@react-native-community/push-notification-ios';
import Toast from "react-native-toast-message"; 
// import { ColorsConstant } from '../constants/Colors.constant';

// import { useNavigation } from '@react-navigation/native';

const NotificationController = (props) => {
  const { navigation } = props;
  // const navigation = useNavigation();
  // console.log("navigationnn", navigation);

  // const NotificationController = () => {
  // const navigation = useNavigation();

  // console.log("asdadddsdfddds", props);
  // const navigation = useNavigation();
  // console.log("navigationnnnn", navigation);
  useEffect(() => {
    const notificationCall = async () => {
      console.log("33333333333333333");
      // hubconn.start().then(() => console.log("Connection Done")).catch((error) => console.log(error));
      // hubconn.on("GetonlineUser", (userList) => {
      //   console.log("999999999999999");
      // });
      // if (AppState.currentState == "background") {
      //   console.log("offfffffffffff99999999999999fffffffffffff")
      //   hubconn.stop().then(() => {
      //     hubconn.on("UserIsOffline", (user) => {
      //       console.log("offffffffffffffffffffffffff")
      //     });
      //   }).catch(e => console.log("errOnStop", e));
      // }

      let unsubscribe = messaging().onMessage(async (remoteMessage) => {
        new_on_notification(remoteMessage)
        console.log("remoteMessageeee", remoteMessage);
      });

      // messaging().registerForRemoteNotifications();   if notifcation not comming   on commant 
      // const token = await messaging().getToken();
      // console.log('TOKEN =', token);

      let granted = await messaging().requestPermission();
      // console.log('GRANTED =', granted);
      messaging().setBackgroundMessageHandler(async remoteMessage => {
        console.log('Message handled in the background!', remoteMessage);
        new_on_notification(remoteMessage)
      });
    }
    notificationCall();
  }, []);

  // useEffect(() => {
  //   const checkToken = async () => {
  //     const fcmToken = await messaging().getToken();
  //     if (fcmToken) {
  //       ///   console.log("fcmToken",fcmToken);
  //     }
  //   }
  //   checkToken();
  // })

  // Must be outside of any component LifeCycle (such as `componentDidMount`).
  PushNotification.configure({
    //   // (required) Called when a remote is received or opened, or local notification is opened
    onNotification: function (notification) {
      console.log("NOTIFICATION22:", notification);
      //     // alert(notification.data.title)
      if (notification.foreground) {
        
        Toast.show({ type: "success", text1: notification.data.title, text2: notification.message });
      }

      if (notification.userInteraction == true) {
        // if (notification.userInteraction != "" || notification.foreground != "") {
        // navigate("PreviousChats")
        console.log("asasassaasaassaasasassasa-------------", notification.data)

        // if (notification.foreground == false && notification.data.type == "Chat") {
        // if (notification.data.type == "Chat") {
        //   // navigation("Messaging", {
        //   navigation("NotificationChat", {
        //     receiverId: notification.data.senderId,
        //     loanId: notification.data.loanId,
        //     applicationNo: notification.data.applicationNo == undefined ? "Without Loan" : notification.data.applicationNo,
        //     type: notification.data.type,
        //   });
        // }
        // navigation("PreviousChats")
        // props.navigation.navigate("PreviousChats")
        // }
        ///alert(notification.data.type)
        //       // if (notification.data.type === "contest") {
        //       // } else if (notification.data.type === "kyc") {
        //       //   navigate("Verify", { refresh: new Date().getTime() });
        //       // } else {
        //       //   navigate("Tabs", { screen: "Home" });
        //       // }
      }
    },

    //   // (optional) Called when Registered Action is pressed and invokeApp is false, if true onNotification will be called (Android)
    // onAction: function (notification) {
    //   console.log("foreground app data------------", notification);
    //   console.log("ACTION:", notification.action);
    //   console.log("NOTIFICATIONonActiononAction:", notification);
    //   if (notification.data.foreground) {
    //     //  new_on_notification(notification)
    //     Toast.show({ type: "success", text1: notification.data.title, text2: notification.message });
    //   }
    //   // process the action
    // },

    //   // (optional) Called when the user fails to register for remote notifications. Typically occurs when APNS is having issues, or the device is a simulator. (iOS)
    //   onRegistrationError: function (err) {
    //     // console.error(err.message, err);
    //   },

    //   // IOS ONLY (optional): default: all - Permissions to register.
    //   permissions: {
    //     alert: true,
    //     badge: true,
    //     sound: true,
    //   },

    //   // Should the initial notification be popped automatically
    //   // default: true
    popInitialNotification: true,

    //   /**
    //    * (optional) default: true
    //    * - Specified if permissions (ios) and token (android and ios) will requested or not,
    //    * - if not, you must call PushNotificationsHandler.requestPermissions() later
    //    * - if you are not using remote notification or do not have Firebase installed, use this:
    //    *     requestPermissions: Platform.OS === 'ios'
    //    */
    requestPermissions: true,
    // }
  });

  const new_on_notification = (message) => {
    console.log("messagekalogg", message)
    PushNotification.createChannel({
      channelId: "finnaux-id", // (required)
      channelName: "finnaux", // (required)
      channelDescription: "A channel to categorise your notifications", // (optional) default: undefined.
      soundName: "default", // (optional) See `soundName` parameter of `localNotification` function
      importance: 4, // (optional) default: 4. Int value of the Android notification importance
      vibrate: true, // (optional) default: true. Creates the default vibration patten if true.
    },
      (created) => console.log(`createChannel returned '${created}'`) // (optional) callback returns whether the channel was created, false means it already existed.
    );

    PushNotification.localNotification({
      /* Android Only Properties */
      channelId: "finnaux-id", // (required) channelId, if the channel doesn't exist, notification will not trigger.
      ticker: "My Notification Ticker", // (optional)
      showWhen: true, // (optional) default: true
      autoCancel: true, // (optional) default: true
      largeIcon: "ic_launcher", // (optional) default: "ic_launcher". Use "" for no large icon.
      // largeIconUrl: "https://www.jagranimages.com/images/newimg/05082022/05_08_2022-ima_22957328.webp", // (optional) default: undefined
      smallIcon: "ic_notification", // (optional) default: "ic_notification" with fallback for "ic_launcher". Use "" for default small icon.
      bigText: message.notification.body, // (optional) default: "message" prop
      // subText: "This is a subText", // (optional) default: none
      // bigPictureUrl: "https://www.example.tld/picture.jpg", // (optional) default: undefined
      bigLargeIcon: "ic_launcher", // (optional) default: undefined
      // bigLargeIconUrl: "https://www.example.tld/bigicon.jpg", // (optional) default: undefined
      // color: "red", // (optional) default: system default
      color: 'orange', // (optional) default: system default
      vibrate: true, // (optional) default: true
      vibration: 300, // vibration length in milliseconds, ignored if vibrate=false, default: 1000
      tag: "some_tag", // (optional) add tag to message
      // group: "group", // (optional) add group to message
      groupSummary: false, // (optional) set this notification to be the group summary for a group of notifications, default: false
      ongoing: false, // (optional) set whether this is an "ongoing" notification
      priority: "high", // (optional) set notification priority, default: high
      visibility: "private", // (optional) set notification visibility, default: private
      ignoreInForeground: false, // (optional) if true, the notification will not be visible when the app is in the foreground (useful for parity with how iOS notifications appear). should be used in combine with `com.dieam.reactnativepushnotification.notification_foreground` setting
      shortcutId: "shortcut-id", // (optional) If this notification is duplicative of a Launcher shortcut, sets the id of the shortcut, in case the Launcher wants to hide the shortcut, default undefined
      onlyAlertOnce: false, // (optional) alert will open only once with sound and notify, default: false
      when: null, // (optional) Add a timestamp (Unix timestamp value in milliseconds) pertaining to the notification (usually the time the event occurred). For apps targeting Build.VERSION_CODES.N and above, this time is not shown anymore by default and must be opted into by using `showWhen`, default: null.
      usesChronometer: false, // (optional) Show the `when` field as a stopwatch. Instead of presenting `when` as a timestamp, the notification will show an automatically updating display of the minutes and seconds since when. Useful when showing an elapsed time (like an ongoing phone call), default: false.
      timeoutAfter: null, // (optional) Specifies a duration in milliseconds after which this notification should be canceled, if it is not already canceled, default: null
      messageId: message.messageId, // (optional) added as `message_id` to intent extras so opening push notification can find data stored by @react-native-firebase/messaging module. 
      actions: ["View", "Ignore"], // (Android only) See the doc for notification actions to know more
      invokeApp: true, // (optional) This enable click on actions to bring back the application to foreground or stay in background, default: true
      // onOpen: () => {
      //   if (message.data.type == "Chat") {
      //     navigation("NotificationChat", {
      //       receiverId: message.data.senderId,
      //       loanId: message.data.loanId,
      //       applicationNo: message.data.applicationNo == undefined ? "Without Loan" : message.data.applicationNo,
      //       type: message.data.type,
      //     });
      //   }
      // },
      // actions: () => {
      //   if (message.data.type == "Chat") {
      //     navigation("NotificationChat", {
      //       receiverId: message.data.senderId,
      //       loanId: message.data.loanId,
      //       applicationNo: message.data.applicationNo == undefined ? "Without Loan" : message.data.applicationNo,
      //       type: message.data.type,
      //     });
      //   }
      // },


      /* iOS only properties */
      // category: "", // (optional) default: empty string
      // subtitle: "My Notification Subtitle", // (optional) smaller title below notification title

      /* iOS and Android properties */
      // id: 0, // (optional) Valid unique 32 bit integer specified as string. default: Autogenerated Unique ID
      // title: message.notification.title, // (optional)
      // message: message.notification.body, // (required)
      // picture: "https://www.example.tld/picture.jpg", // (optional) Display an picture with the notification, alias of `bigPictureUrl` for Android. default: undefined
      // userInfo: {}, // (optional) default: {} (using null throws a JSON value '<null>' error)
      // playSound: true, // (optional) default: true
      // soundName: "default", // (optional) Sound to play when the notification is shown. Value of 'default' plays the default sound. It can be set to a custom sound such as 'android.resource://com.xyz/raw/my_sound'. It will look for the 'my_sound' audio file in 'res/raw' directory and play it. default: 'default' (default sound is played)
      // number: 1, // (optional) Valid 32 bit integer specified as string. default: none (Cannot be zero)
      // // repeatType: "day", // (optional) Repeating interval. Check 'Repeating Notifications' section for more info.
    });
  }

};

export default NotificationController;