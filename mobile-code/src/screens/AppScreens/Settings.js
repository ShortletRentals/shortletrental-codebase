import React, { useContext, useState } from 'react';
import {
  View,
  Image,
  Text,
  Switch,
  ImageBackground,
  TouchableOpacity,
  _Text,
  ScrollView,
  ActivityIndicator,
  Platform,
  Linking,
} from 'react-native';
import { color, height, width } from '../../styles/colors';
import Images from '../../styles/Images';
import { commonStyles } from './../../styles/style';
import { Colors, colors } from 'react-native/Libraries/NewAppScreen';
import Header from '../../components/Header';
import TextView from '../../components/TextView';
import axios from 'axios';
import AsyncStorage from '@react-native-async-storage/async-storage';
import { useFocusEffect, useNavigation, useRoute } from '@react-navigation/native';
import { Logout, MAIN_URL, Notification_On_OFF } from '../../network/Webconstant';
import { Context as AuthContext } from '../../context/AuthContext';
import { Toast } from 'react-native-toast-message/lib/src/Toast';
import { BackHandler } from 'react-native';
import { useEffect } from 'react';
import { GoogleSignin } from '@react-native-google-signin/google-signin';

const Setting = props => {
  // const {token} = route.params
  const navigation = useNavigation();
  const [mobileNumber, setMobileNumber] = useState('');
  const [conformPassword, SetPassword] = useState('');
  const [isEnabled, setIsEnabled] = useState(props?.route?.params?.notification == "Off" ? false : true);
  // const toggleSwitch = () => setIsEnabled(previousState => !previousState);
  const [user, setUser] = useState('');
  const [token_id, setToken_Id] = useState('');
  const [loader, setLoader] = useState(false)
  const route = useRoute()
  const {
    logoutUser,
    state: { Token_ID },
  } = useContext(AuthContext);
  // console.log('====token logout', Token_ID);

  const handler = () => {
    props.navigation.navigate('Account')
  }
  useFocusEffect(
    React.useCallback(() => {
      const backAction = () => {
        handler()
        return true;
      };

      const backHandler = BackHandler.addEventListener(
        'hardwareBackPress',
        backAction,
      );

      return () => backHandler.remove();
    }, [props]))
  const onformlogout = async () => {
    const Localtoken = await AsyncStorage.getItem('token_id');
    // await AsyncStorage.removeItem('USER_ID');
    // await AsyncStorage.removeItem('gName');
    // await AsyncStorage.removeItem('gEmail');
    // navigation.navigate('BeforHome');
    setLoader(true)
    try {
      await axios({
        method: 'post',
        url: Logout,
        headers: {
          Accept: "application/json",
          Authorization: `Bearer ${Localtoken}`
        }
      }).then(
        async function (res) {
          if (res.data.status == true) {
            setLoader(false)
            Toast.show({
              type: 'success',
              text1: 'Shortlet',
              text2: res.data.message
            })
            await AsyncStorage.removeItem('USER_ID');
            await AsyncStorage.removeItem('token_id');
            
            navigation.navigate('BeforHome');
            await GoogleSignin.signOut()
          } else {
            Toast.show({
              type: 'success',
              text1: 'Shortlet',
              text2: res.data.message
            })
            setLoader(false)
          }
        }
      )
    } catch (error) {
      console.log('error logout', error);
      await AsyncStorage.removeItem('USER_ID');
      await AsyncStorage.removeItem('token_id');
      navigation.navigate('BeforHome');
      setLoader(false)
    }

  };

  const notificationOnOffApiHandler = async () => {
    const Localtoken = await AsyncStorage.getItem('token_id');
    try {
      axios({
        method: 'post',
        url: Notification_On_OFF,
        data: { on_off: isEnabled ? "Off" : "On" },
        headers: { "Accept": "application/json", "Authorization": `Bearer ${Localtoken}` }
      }).then(
        function (res) {
          // console.log('================res', res.data.status)
          if (res.data.status == true) {
            Toast.show({
              type: 'success',
              text1: 'Shortlet',
              text2: res.data.message
            })
          } else {
            Toast.show({
              type: 'error',
              text1: 'Shortlet',
              text2: res.data.message
            })
          }
        }
      )
    } catch (error) {
      console.log('error is notification', error);
    }
  }
  // console.log('=========enable', isEnabled, props.route.params.notification);
  return (
    <View style={[commonStyles.container, { color: color.appWhiteColor }]}>
      <Header
        props={props}
        Heading={'Settings'}
        onPress={() => props.navigation.navigate('Account')}
      />
      <ScrollView contentContainerStyle={{ flexGrow: 1 }}>
        <TouchableOpacity
          style={{
            borderRadius: 10,
            marginTop: 20,
            height: 60,
            marginHorizontal: 20,
            padding: 16,
            backgroundColor: color.appLightOrangeColor,
            borderColor: color.appOrangeColor,
            borderWidth: 2,
            flexDirection: 'row',
            justifyContent: 'space-between',
            alignItems: 'center',
          }}>
          <Text style={{ color: color.appOrangeColor, fontSize: 14 }}>
            Notifications
          </Text>
          {/* <Image source={Images.ArrowIcons} /> */}
          <View
            style={{
              height: 28,
              backgroundColor: isEnabled ? 'orange' : '#767577',
              width: 50,
              borderRadius: 20,
              justifyContent: 'center',
            }}>
            <Switch
              trackColor={{ false: '#767577', true: 'orange' }}
              thumbColor={isEnabled ? '#fff' : '#f5dd4b'}
              ios_backgroundColor="#3e3e3e"
              onValueChange={() => {
                setIsEnabled(!isEnabled)
                notificationOnOffApiHandler()
              }}
              value={isEnabled}
              style={{ marginLeft: Platform.OS == 'ios' ? 0 : 20 }}

            // f5dd4b
            />
          </View>
        </TouchableOpacity>
        {/* <TouchableOpacity
          onPress={() => props.navigation.navigate('ChangePassword')}
          style={{
            borderRadius: 10,
            marginTop: 15,
            height: 60,
            marginHorizontal: 20,
            padding: 16,
            backgroundColor: color.appLightOrangeColor,
            borderColor: color.appOrangeColor,
            borderWidth: 2,
            flexDirection: 'row',
            justifyContent: 'space-between',
            alignItems: 'center',
          }}>
          <Text style={{color: color.appOrangeColor, fontSize: 14}}>
            Change Password
          </Text>
          <Image source={Images.ArrowIcons} />
        </TouchableOpacity> */}
        <TouchableOpacity
          onPress={() => props?.navigation?.navigate('AboutUs')}
          style={{
            borderRadius: 10,
            marginTop: 20,
            height: 60,
            marginHorizontal: 20,
            padding: 16,
            backgroundColor: color.appLightOrangeColor,
            borderColor: color.appOrangeColor,
            borderWidth: 2,
            flexDirection: 'row',
            justifyContent: 'space-between',
            alignItems: 'center',
          }}>
          <Text style={{ color: color.appOrangeColor, fontSize: 14 }}>
            About Us
          </Text>
          <Image source={Images.ArrowIcons} />
        </TouchableOpacity>
        <TouchableOpacity
          onPress={() => props?.navigation?.navigate('Contact')}
          style={{
            borderRadius: 10,
            marginTop: 20,
            height: 60,
            marginHorizontal: 20,
            padding: 16,
            backgroundColor: color.appLightOrangeColor,
            borderColor: color.appOrangeColor,
            borderWidth: 2,
            flexDirection: 'row',
            justifyContent: 'space-between',
            alignItems: 'center',
          }}>
          <Text style={{ color: color.appOrangeColor, fontSize: 14 }}>
            Contact Us
          </Text>
          <Image source={Images.ArrowIcons} />
        </TouchableOpacity>
        {/* <TouchableOpacity onPress={()=>props?.navigation?.navigate('Help')}
          style={{
            borderRadius: 10,
            marginTop: 15,
            height: 60,
            marginHorizontal: 20,
            padding: 16,
            backgroundColor: color.appLightOrangeColor,
            borderColor: color.appOrangeColor,
            borderWidth: 2,
            flexDirection: 'row',
            justifyContent: 'space-between',
            alignItems: 'center',
          }}>
          <Text style={{color: color.appOrangeColor, fontSize: 14}}>
            Help Center
          </Text>
          <Image source={Images.ArrowIcons} />
        </TouchableOpacity> */}
        <View style={{ flex: 1, justifyContent: 'flex-end' }}>
          <View
            style={{
              flexDirection: 'row',
              alignSelf: 'center',
              marginBottom: 10,
            }}>
              <TouchableOpacity  onPress={() =>
                Linking.openURL(
                  `${MAIN_URL}/privacy-policy`,
                )
              }>
            <Text style={{ color: color.primaryColorBlack, fontSize: 16 }}>
              Privacy Policy
            </Text>
            </TouchableOpacity>
            <View style={{ backgroundColor: '#F99428', height: 3, width: 3, borderRadius: 1.5, alignSelf: 'center', marginHorizontal: 6 }} />
           <TouchableOpacity onPress={() =>
                Linking.openURL(
                  `${MAIN_URL}/cancellation-policy`,
                )
              }>
            <Text style={{ color: color.primaryColorBlack, fontSize: 16 }}>
              Cancellation Policy
            </Text>
            </TouchableOpacity>
          </View>
          <Text
            style={{
              alignSelf: 'center',
              color: color.primaryColorBlack,
              fontSize: 14,
            }}>
            App v3.25
          </Text>
          <TouchableOpacity
            onPress={onformlogout}
            // {onformLogout}
            // onPress={()=>props.navigation.navigate('Logins')}
            style={{
              marginTop: 30,
              backgroundColor: color.appOrangeColor,
              width: '90%',
              padding: 10,
              borderRadius: 10,
              justifyContent: 'center',
              alignSelf: 'center',
              marginBottom: width * (20 / 375),
              height: 50,
              marginHorizontal: 20,
            }}>
            <View
              style={{
                position: 'absolute',
                flex: 1,
              }}>
              <Image
                source={Images.whiteDot}
                style={{
                  paddingLeft: 70,
                  width: 25,
                  height: 25,
                  resizeMode: 'contain',
                }}
              />
            </View>

            <Text
              style={{
                textAlign: 'center',
                fontSize: 15,
                color: color.appWhiteColor,
              }}>
              Logout
            </Text>
          </TouchableOpacity>
          {
            loader ? <View style={{ top: -55, right: -70 }}><ActivityIndicator size={'small'} color='#fff' /></View> : null
          }
        </View>
      </ScrollView>
    </View>
  );
};

export default Setting;
