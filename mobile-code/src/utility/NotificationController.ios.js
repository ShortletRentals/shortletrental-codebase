import { useEffect } from 'react';
import { Alert } from 'react-native';
import messaging from '@react-native-firebase/messaging';
import PushNotification from 'react-native-push-notification';
import PushNotificationIos from '@react-native-community/push-notification-ios';
import { Toast } from 'react-native-toast-message/lib/src/Toast';

const NotificationController = (props) => {
  // const navigation = useNavigation();
  // Called when application is open by clicking on notification
  // and called when application is already opend and user click on notification

  PushNotification.configure({
    onNotification: (notification) => {
      if (notification) {
        // console.log(notification);
        // Alert.alert('Opened push notification', JSON.stringify(notification));
      }
    },
  });

  useEffect(() => {
    // Usesd to display notification when app is in foreground
    const unsubscribe = messaging().onMessage(async (remoteMessage) => {
      console.log("remoteMessage---------", remoteMessage)
      Toast.show({ type: 'success', text1: remoteMessage.notification.title,text2:remoteMessage.notification.body });

      // PushNotificationIos.scheduleLocalNotification({
      //   id: remoteMessage.messageId,
      //   body: remoteMessage.notification.body,
      //   title: remoteMessage.notification.title,
      //   userInfo: remoteMessage.data,
      // });
      // PushNotificationIos.
    });

    return unsubscribe;
  }, []);

  return null;
};

export default NotificationController;