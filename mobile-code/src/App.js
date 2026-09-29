import { NavigationContainer, useNavigation, useNavigationContainerRef } from '@react-navigation/native';
import React, { useEffect } from 'react';
import { View, Text, DeviceEventEmitter, BackHandler, StatusBar, SafeAreaView, Alert,StyleSheet } from 'react-native';
import AuthStack from './navigators/AuthStack';

import { Provider as AuthProvider } from './context/AuthContext';
import { Provider as HomeProvider } from './context/HomeContext';
import { Provider as BookingProvider } from './context/BookingContext';
import { Provider as CardProvider } from './context/CardContext';
import { Provider as ProfileProvider } from './context/ProfileContext';
import { Toast } from 'react-native-toast-message/lib/src/Toast';
import AsyncStorage from '@react-native-async-storage/async-storage';
import { useState } from 'react';
import AppStack from './navigators/AppStack';
import TouchID from 'react-native-touch-id';
import NotificationControllerAndroid from './utility/NotificationController.android';
import NotificationControllerIos from './utility/NotificationController.ios';
// import { useNetInfo, NetInfoState } from "@react-native-community/netinfo";

// import messaging from '@react-native-firebase/messaging';
import { useNetInfo, NetInfoState } from "@react-native-community/netinfo";

const App = () => {
  const [user, setUser] = useState(null)
  const [loader, setLoader] = useState(false)
  // const store = ConfigureStore)


  // const checkToken = async () => {
  //  const fcmToken = await messaging().getToken();
  //  if (fcmToken) {
  //     console.log("----tokenn-------",fcmToken);
  //  } 
  // }

  useEffect(() => {
    getUserID()
    // checkToken()

  }, [])
  // const internetState = useNetInfo()
  //     useEffect(() => {
  //   console.log('----internnet------',internetState);
  //   if (internetState.isConnected === false) {
  //     Alert.alert(
  //       "No Internet! ❌",
  //       "Sorry, we need an Internet connection for MY_APP to run correctly.",
  //       [{ text: "Okay"  } ]
  //     );
  //   }
  // }, [internetState.isConnected]);

  // const [d, setD] = useState("12/6/2023, 6:09:35 PM")


  const optionalConfigObject = {
    title: 'Authentication Required', // Android
    imageColor: '#e00606', // Android
    imageErrorColor: '#ff0000', // Android
    sensorDescription: 'Touch sensor', // Android
    sensorErrorDescription: 'Failed', // Android
    cancelText: 'Cancel', // Android

    // fallbackLabel: 'Show Passcode', // iOS (if empty, then label is hidden)
    unifiedErrors: false, // use unified error messages (default false)
    // passcodeFallback: false, // iOS - allows the device to fall back to using the passcode, if faceid/touch is not available. this does not mean that if touchid/faceid fails the first few times it will revert to passcode, rather that if the former are not enrolled, then it will use the passcode.
  };


  const clickBiometricHandler = () => {
    TouchID.isSupported(optionalConfigObject)
      .then(biometricType => {
        if (biometricType === 'FaceID') {
          console.log("Face id suported")
        } else {
          // console.log("Touch id supported");
          TouchID.authenticate("", optionalConfigObject).then(result => {
            console.log('------------result------Biometric Handler------------', result)
          }).catch((err) => {
            // console.log('error is Biometric Handler', err);
            clickBiometricHandler()
          })
        }
      })
  }
  const getUserID = async () => {
    let id = await AsyncStorage.getItem('USER_ID')
    if (id !== null) {
      setLoader(true)
      clickBiometricHandler()
    } else {
      setLoader(false)
    }
    setUser(id)
  }


  


const internetState  = useNetInfo();

useEffect(() => {
  console.log('======internet======,',internetState  )
  if (internetState.isConnected === false) {
    Alert.alert(
      "No Internet! ❌",
      "Sorry, we need an Internet connection for MY_APP to run correctly.",
      [{ text: "Okay" }]
    );
  }
}, [internetState.isConnected]);

if (internetState.isConnected === false) {
  return (
    <View style={styles.centered}>
      <Text style={styles.title}>
        Please turn on the Internet to use MY_APP SHORTLET RENTAL.
      </Text>
    </View>
  );
}



  



  // useEffect(() => {
  //   clickBiometricHandler()
  // }, [])
  {/* <Text style={{marginTop:100,color:'red'}}>{moment(d, "M/D/YYYY, h:mm:ss A").format("D-MM-YYYY")}</Text>  */ }
  return (
    < NavigationContainer style={{ flex: 1 }}>
      < StatusBar backgroundColor={'transparent'} translucent />
       {Platform.OS == "ios"?
            <NotificationControllerIos/>:
            <NotificationControllerAndroid/>
            }
      {
        loader ? <AuthStack /> : <AppStack />
      }



      <Toast position="top"  />
      {/* </SafeAreaView> */}
    </NavigationContainer >

  );
};

export default () => {
  return (
    <ProfileProvider>
      <CardProvider>
        <BookingProvider>
          <HomeProvider>
            <AuthProvider>
              <App />
            </AuthProvider>
          </HomeProvider>
        </BookingProvider>
      </CardProvider>
    </ProfileProvider>

  );
};

const styles = StyleSheet.create({
  centered: {
    alignItems: "center",
    flex: 1,
    justifyContent: "center",
  },
  title: {
    fontSize: 20,
    fontWeight: "bold",
    textAlign: "center",
  },
});
