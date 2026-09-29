import React, { useContext, useEffect, useState } from 'react';
import {
  View,
  Image,
  Text,
  ImageBackground,
  TouchableOpacity,
  _Text,
  ScrollView,
  TextInput,
  ActivityIndicator,
  Switch,
  Platform,
  useColorScheme,
  useWindowDimensions,
} from 'react-native';
import { color, height, width } from '../../styles/colors';
import Images from '../../styles/Images';
import { commonStyles } from './../../styles/style';
import { Colors, colors } from 'react-native/Libraries/NewAppScreen';
import Header from '../../components/Header';
import TextView from '../../components/TextView';
import CheckBox from '@react-native-community/checkbox';
import axios from 'axios';
import FlashMessage, { showMessage } from 'react-native-flash-message';
import AsyncStorage from '@react-native-async-storage/async-storage';
import { useIsFocused, useNavigation, useRoute } from '@react-navigation/native';
import { Forgot_Password, GoogleSocialLogin, Host_Url, Login_Url } from '../../network/Webconstant';
import { Context as AuthContext } from '../../context/AuthContext';
import { customToast } from '../../components/ToastMessage';
import { Toast } from 'react-native-toast-message/lib/src/Toast';
import CountryPicker from 'react-native-country-picker-modal'
import TouchID from 'react-native-touch-id';
// import { err } from 'react-native-svg/lib/typescript/xml';
// import { TouchID } from 'react-native-biometrics';
import PushNotification from 'react-native-push-notification';
import { onFacebookLogin } from '../../components/FacebookLogin';
import { GoogleSignin, statusCodes } from '@react-native-google-signin/google-signin';
import messaging from '@react-native-firebase/messaging';


const Logins = props => {
  const route = useRoute();
  const isfocus = useIsFocused()
  // 8561028424
  // const  { mobiles,country_codes,password } = route.params.mobileNumber
  const navigation = useNavigation();
  const [mobileNumber, setMobileNumber] = useState('');
  const [conformPassword, SetPassword] = useState('');
  const [toggleCheckBox, setToggleCheckBox] = useState(false);
  const [token, setToken] = useState('');

  const [email, setEmail] = useState('');
  const [isSelect, setIsSelect] = useState(true);
  const [showLoader, setShowLoader] = useState(false);
  const [isChecked, setIsChecked] = useState(true);
  const [country, setCountry] = useState('234')
  const [countryCode, setCountryCode] = useState('NG')
  const [visible, setVisible] = useState(false)
  const [forgotOtp, setForgotOtp] = useState(0)
  const [togleBtn, setTogleBtn] = useState(false)

  const {
    signInUser,
    forgotPassword,
    state: { activityIndicator = false, Mobile, country_code, Token_ID },
  } = useContext(AuthContext);

  useEffect(() => {
    if (isChecked == false) {
      storeUser();
    }

    // getUser();
  }, [isChecked]);

  useEffect(() => {
    getUser()
  }, [isfocus])


  useEffect(() => {
    getEmail()
    storeUser();
    GoogleSignin.configure({
      webClientId: ""
    })
    checkToken()
  }, [props]);

  const getEmail =async ()=>{
let value = await AsyncStorage.getItem('User_Data')
let obj = JSON.parse(value)
console.log('=-=-=-value',obj);
setEmail(obj.email)
  }

  console.log('-=-=-=emnail',email);
  const checkToken = async () => {
    const fcmToken = await messaging().getToken();
    if (fcmToken) {
      console.log("---fcm-tokenn-------", fcmToken);
      setToken(fcmToken)
    }
  }

  const onClickGoogleLoginHandler = async () => {
    try {
      await GoogleSignin.hasPlayServices();
      const userInfo = await GoogleSignin.signIn();
      console.log('userINfo', userInfo)
      await axios({
        method: 'post',
        url: GoogleSocialLogin,
        data: {
          social_type: "google",
          social_id: userInfo.user.id,
          email: userInfo.user.email,
          name: userInfo.user.name,
          image : userInfo?.user?.photo,
          device_type: Platform.OS == "android" ? "Android" : "IOS",
          device_token: token
        },

      }).then(

        async function (response) {
          console.log('response login ', response.data)
          if (response.data.status == true) {
            // await AsyncStorage.setItem('gEmail', userInfo.user.email)
            // await AsyncStorage.setItem('gName', userInfo.user.name)
            await AsyncStorage.setItem('token_id', response.data.token)
            props.navigation.reset({
              index: 0,
              routeNames: ['BottomTab'],
              routes: [{ name: 'BottomTab' }]
            })
            Toast.show({
              type: 'success',
              text1: 'Shortlet',
              text2: response.data.message
            })
            // props?.navigation?.navigate('BottomTab')

          } else {
            Toast.show({
              type: 'error',
              text1: 'Shortlet',
              text2: response.data.message
            })
          }
        }
      ).catch(async(e) => {
        console.log('googlesign', e);
        await GoogleSignin.signOut()
      })
    } catch (error) {
      if (error.code === statusCodes.SIGN_IN_CANCELLED) {
        console.log('sign in cancelled', error);
        // user cancelled the login flow
      } else if (error.code === statusCodes.IN_PROGRESS) {
        // operation (e.g. sign in) is in progress already
        console.log('in progress', error);
        await GoogleSignin.signOut()

      } else if (error.code === statusCodes.PLAY_SERVICES_NOT_AVAILABLE) {
        // play services not available or outdated
        console.log('play services not available', error);
        await GoogleSignin.signOut()

      } else {
        // some other error happened
        console.log('error in end', error);
        await GoogleSignin.signOut()

      }
    }
  }
  // console.log('ischecked', global.fcmToken)

  const onClickFacebookLoginHandler = async () => {
    onFacebookLogin().then(async (data, token) => {
      console.log('facebook login', data);
    })
  }
  // storing data
  const storeUser = async () => {
    try {
      let data = {
        mobileNumber: mobileNumber,
        conformPassword: conformPassword,
      };
      await AsyncStorage.setItem('userData_Local', JSON.stringify(data));
    } catch (error) {
      console.log(error);
    }
  };

  // getting data
  const getUser = async () => {
    try {
      const userData = JSON.parse(await AsyncStorage.getItem('userData_Local'));
      console.log('userData', userData);
      // if(userData?.conformPassword == ""){
      //   setIsChecked(true)
      // }
      if (userData.mobileNumber != '') {
        setMobileNumber(userData.mobileNumber)
        SetPassword(userData.conformPassword)
        setIsChecked(false)

      } else {
        setMobileNumber('')
        SetPassword("")

      }
    } catch (error) {
      console.log(error);
    }
  };

  useEffect(() => {
    if (isChecked == true) {
      removeValue();
      // setMobileNumber('')
      // SetPassword("")
    }


    // PushNotification.configure({
    //   // (optional) Called when Token is generated (iOS and Android)
    //   onRegister: function (token) {
    //     console.log('TOKEN:', token);
    //   },

    // })
  }, [isfocus, isChecked]);

  const removeValue = async () => {
    try {
      await AsyncStorage.removeItem("userData_Local")
    } catch (error) {
      console.log('error', error);
    }
  }

  const onSelect = (country) => {
    setCountryCode(country.cca2)
    setCountry(country.callingCode[0])
  }
  const loginHandler = () => {
    let re = /^([+]\d{2})?\d{8,12}$/;

    let regex =
      /^(([^<>()[\]\.,;:\s@\"]+(\.[^<>()[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/i;

    // let isEmailCorrect = false;
    // if (regex.test(mobileNumber)) {
    //   isEmailCorrect = true;
    // }

    // let isMobileCorrect = false;
    // if (parseInt(mobileNumber) !== isNaN && re.test(mobileNumber)) {
    //   console.log('Mobile False');
    //   isMobileCorrect = true;
    // }

    // if (isMobileCorrect || isEmailCorrect) {
    //   let type = isMobileCorrect ? 'Mobile' : 'Email';
    if (mobileNumber == '') {
      Toast.show({
        type: 'error',
        text1: 'Shortlet',
        text2: 'Please enter mobile number.'
      })
    }else if(re.test(mobileNumber) === false){
      Toast.show({
        type: 'error',
        text1: 'Shortlet',
        text2: 'Please enter 8-12 digit mobile number.'
      })
    }
    else if (conformPassword == '') {
      Toast.show({
        type: 'error',
        text1: 'Shortlet',
        text2: 'Please enter password'
      })
    }
    else if (conformPassword !== '') {
      var regularExpression = /^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#\$%\^&\*])(?=.{8,15})/

      if (regularExpression.test(conformPassword) === false) {
        Toast.show({
          type: 'error',
          text1: 'Your password must contain at least (1) lowercase, (1)',
          text2: ' uppercase letter, (1) special character and minimum 8 characters.'
        })
      
      } else {
        onLoginformHandler();

      }

    }

  }


  //   else {
  // Toast.show({
  //   type: 'error',
  //   text1: 'Shortlet',
  //   text2: 'Mobile no is not registerd please sign up or Password',
  // });
  // setShowLoader(false)


console.log('=====country', country, togleBtn);
console.log('handle login called', token);

const onLoginformHandler = () => {
  // console.log(type, '======type')
  setShowLoader(true)
  try {
    axios({
      method: 'post',
      // url: togleBtn ? Login_Url : Host_Url,
      url: Login_Url,
      data: {
        type: "Mobile",
        country_code: country,
        mobile: mobileNumber,
        password: conformPassword,
        // email: mobileNumber,
        device_type: Platform.OS == "android" ? "Android" : "IOS",
        device_token: token
        // device_token: global.fcmToken === undefined ? global.fcmToken?.token ? global.fcmToken?.token : "" : ""
      },
    })
      .then(async function (response) {
        console.log( 'res', response.data.message);
        if (response.data.status === true) {
          await AsyncStorage.setItem('token_id', response.data.token);
          await AsyncStorage.setItem('USER_ID', JSON.stringify(response.data.data.id));
          await AsyncStorage.setItem(
            'User_Data',
            JSON.stringify(response.data.data),
          );
          Toast.show({
            type: 'success',
            text1: 'Shortlet',
            text2: response.data.message,
          });
          setMobileNumber('');
          SetPassword('');
          setShowLoader(false);
          props.navigation.reset({
            index: 0,
            routeNames: ['BottomTab'],
            routes: [{ name: 'BottomTab' }]
          })
          // togleBtn ?
          // props.navigation.reset({
          //   index: 0,
          //   routeNames: ['BottomTab'],
          //   routes: [{ name: 'BottomTab' }]
          // }) :  props.navigation.navigate('BecomeaHostHome') 
        } else {
          Toast.show({
            type: 'error',
            text1: 'Shortlet',
            text2: response.data.message,
          });
          setShowLoader(false)
        }
      })

  } catch (error) {
    console.log('error logins', error);
    setShowLoader(false)
  }




};

const onSubmitForgotPasswordApi = () => {
  let data = { country_code: country, mobile: mobileNumber };
  try {
    axios({
      method: 'post',
      url: Forgot_Password,
      data: data
    })
      .then(function (response) {
        console.log('ressssss forgot ', response.data)
        if (response.data.status === 1) {
          // setForgotOtp(response.data.data)
          Toast.show({
            type: 'success',
            text1: 'Shortlet',
            text2: response.data.message,
          });
          navigation.navigate('Otp', { mobiles: mobileNumber, countryForgot: country, FORGOTPASSWORD: response.data.data, fromForgot: 'forgot',EMAIL:email });
        } else {
          Toast.show({
            type: 'error',
            text1: 'Shortlet',
            text2:
              response.data.message.mobile ||
              response.data.message.country_code ||
              response.data.message,
          });
        }
      })
      .catch(error => {
        console.log('error', error);
      });
  } catch (error) {
    console.log('e', error);
  }

  // forgotPassword(data, () => {
  //   navigation.navigate('Otp', { mobiles: mobileNumber,countryForgot:country });
  // });
};


return (
  <View style={commonStyles.container}>
    <Header
      props={props}
      Heading={'Login'}
      onPress={() => props.navigation.goBack()}
    />
    <ScrollView showsVerticalScrollIndicator={false}>
      <Image
        source={Images.mobileIcon}
        style={{
          width: width / 2,
          height: height / 3.8,
          resizeMode: 'stretch',
          alignSelf: 'center',
          marginTop: 10,
          marginBottom: 10,
        }}
      />
      {/* <View style={{ flexDirection: 'row', justifyContent: "center", alignItems: 'center', marginHorizontal: 20, paddingVertical: 20 }}>
          <Text style={{ color: "#707070", fontSize: 15, fontFamily: 'Gill Sans', }}>Host Login</Text>
          <View style={{ height: 28, backgroundColor: '#F99428', width: 50, borderRadius: 20, justifyContent: 'center', marginHorizontal: 15 }}>
            <Switch
            
            value={togleBtn}
              onValueChange={() => setTogleBtn(!togleBtn)}

              trackColor={{ false: '#F99428', true: '#F99428' }}
              thumbColor={"#fff"}
              ios_backgroundColor="#3e3e3e"
            />
          </View>
          <Text style={{ color: "#707070", fontSize: 15, fontFamily: 'Gill Sans', }}>User Login</Text>
        </View> */}

      <View style={{ flexDirection: 'row', justifyContent: 'flex-start', alignItems: 'center', marginHorizontal: 20 }}>
        {/* {
            parseInt(mobileNumber) ? */}
        <View style={{ width: '30%', flexDirection: 'row', alignItems: 'center', justifyContent: 'center', marginRight: 10 }}>
          <View style={{ height: 50, backgroundColor: color.appTextBackgoundColor, flexDirection: 'row', alignItems: 'center', padding: 10, borderRadius: 8, marginTop: 10 }}>
            <CountryPicker
              {...{
                onSelect,
              }}
              visible={visible}
              countryCode={countryCode}
              withFilter={true}
            // containerButtonStyle={{backgroundColor:'#fff'}}

            />

            <Text style={{ color: '#000' }}>+{country}</Text>
          </View>
        </View>
        {/* : null} */}


        <TextInput
          placeholder={'Mobile Number'}
          // placeholder={'Mobile Number/Email Address'}
          value={mobileNumber}
          // onChangeText={text => {
          //   parseInt(mobileNumber) ?
          //     setMobileNumber(text.replace(/[^0-9]/g, '')) : setMobileNumber(text.replace(/[^A-Za-z0-9-@A-Za-z0-9.A-Za-z0-9]/g, ""))

          // }}
          onChangeText={(text) => setMobileNumber(text)}
          // isNumeric={true}
          // keyboardType={parseInt(mobileNumber) ? 'numeric' : 'email-address'}
          // maxLength={parseInt(mobileNumber) ? 15 : 120}
          keyboardType='numeric'
          maxLength={12}
          style={{
            height: 50,
            // width: parseInt(mobileNumber) ? '70%' : '100%',
            width: '67%',
            // margin: 10,
            marginTop: 10,
            backgroundColor: color.appTextBackgoundColor,
            borderRadius: 10,
            paddingHorizontal: 14,
            fontSize: 15, color: '#000'
          }} placeholderTextColor={color.appTextColor}
        />
      </View>
      {/* <FlashMessage position={'top'} /> */}
      <View
        style={{
          marginTop: 10,
          marginBottom: 10,
          flexDirection: 'row',
          justifyContent: 'space-between',
          alignItems: 'center',
          height: 50,
          backgroundColor: color.appTextBackgoundColor,
          marginHorizontal: 20,
          borderRadius: 10,
        }}>
        <TextInput
          placeholder={'Password'}
          value={conformPassword}
          onChangeText={e => SetPassword(e)}
          secureTextEntry={isSelect}
          style={{
            height: 50,
            width: '80%',
            margin: 10,
            backgroundColor: color.appTextBackgoundColor,
            borderRadius: 10,
            color: '#000',
            fontSize: 15,
          }} placeholderTextColor={color.appTextColor}
        />
        <TouchableOpacity onPress={() => setIsSelect(!isSelect)}>
          <Image
            source={isSelect ? Images.hiddenIcon : Images.eyeIcon}
            style={{ height: 20, width: 20, marginRight: 20 }}
          />
        </TouchableOpacity>
      </View>

      <FlashMessage position={'center'} />
      <View
        style={{ flexDirection: 'row', marginTop: 10, marginHorizontal: 20 }}>

        <TouchableOpacity
          onPress={() => {
            setIsChecked(!isChecked)
          }}
          activeOpacity={0.8}
          style={{
            flexDirection: 'row',
            alignItems: 'center',
            marginVertical: 10,
            // marginHorizontal: 10,
          }}>
          {isChecked ? (
            <Image
              source={Images.checkblank}
              style={{ height: 24, width: 24 }}
            />
          ) : (
            <Image
              source={Images.checkfull}
              style={{ height: 24, width: 24 }}
            />
          )}

          <Text
            style={{
              paddingLeft: 10,
              paddingRight: 10,
              fontSize: 15,
              marginHorizontal: 4, color: '#000'
            }}>
            Remember Me
          </Text>
        </TouchableOpacity>
      </View>

      <TouchableOpacity
        activeOpacity={0.5}
        onPress={
          () => {
            loginHandler();
          }
        }
        style={{
          marginTop: 17,
          backgroundColor: color.appOrangeColor,
          width: '90%',
          // padding: 10,
          height: 50,
          borderRadius: 10,
          justifyContent: 'center',
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
              width: 29,
              height: 29,
              resizeMode: 'contain',
            }}
          />
        </View>
        <View style={{ flexDirection: 'row', alignSelf: 'center', position: 'absolute' }}>
          <Text
            style={{
              textAlign: 'center',
              fontSize: 16,
              color: color.appWhiteColor,
              alignSelf: 'center'
            }}>
            Login
          </Text>
          <View>
            {
              showLoader ?
                <View style={{ marginLeft: 10 }}>
                  <ActivityIndicator size="small" color="#fff" />
                </View >
                : null
            }
          </View>
        </View>
      </TouchableOpacity>
      {/* </View> */}
      <TouchableOpacity onPress={onSubmitForgotPasswordApi}>
        <Text
          style={{
            color: color.appOrangeColor,
            textAlign: 'center',
            fontSize: 18,
            marginTop: 10,
          }}>
          Forgot Password
        </Text>
      </TouchableOpacity >

      <View
        style={{
          // justifyContent: "flex-end",
          // bottom: 30,
          // flex: 1,
          alignItems: 'center',
          marginTop: 50,
        }}>
        <View
        // onPress={() => clickBiometricHandler()}
        >
          <Image
            source={Images.BioMetric}
            style={{
              paddingLeft: 70,
              width: 50,
              height: 50,
              resizeMode: 'contain',
              // bottom: 30,
              alignSelf: 'center',
              // position: 'absolute'
            }}
          />
        </View>
        <View style={{ flexDirection: 'row', marginTop: 21, marginBottom: 10 }}>
          <Text style={{ fontSize: width * (15 / 375), color: '#000' }}>
            If you Don't have an account ?
          </Text>
          <TouchableOpacity onPress={() => navigation.navigate('SignUp')}>
            <Text
              style={{
                color: color.appOrangeColor,
                fontSize: width * (15 / 375),
              }}>
              Sign Up
            </Text>
          </TouchableOpacity>
        </View >
      </View >
      <Text
        style={{
          fontWeight: 'bold',
          fontSize: 20,
          alignSelf: 'center',
          marginTop: 15,
          color: color.primaryColorBlack,
        }}>
        Social Media Login
      </Text>
      <View
        style={{
          flexDirection: 'row',
          // width: width * (180 / 375),
          height: 40,
          alignSelf: 'center',
          justifyContent: 'flex-start',
          alignItems: 'center',
          marginVertical: 15,
        }}>
        <TouchableOpacity onPress={() => onClickFacebookLoginHandler()}>
          <Image
            source={Images.fbIcon}
            style={{
              width: 40,
              height: 40,
              resizeMode: 'contain',
              paddingLeft: 50,
            }}
          />
        </TouchableOpacity>
        <TouchableOpacity onPress={() => onClickGoogleLoginHandler()}>
          <Image
            source={Images.googleIcon}
            style={{
              width: 40,
              height: 40,
              resizeMode: 'contain',
            }}
          />
        </TouchableOpacity>
        {/* <TouchableOpacity onPress={() => Linking.openURL('https://www.apple.com/in')}>
                        <Image source={Images.appleIcon} style={{
                            width: 40, height: 40, resizeMode: 'contain', paddingLeft: 70,
                        }} />
                    </TouchableOpacity> */}
      </View>
    </ScrollView >
  </View >
);
};

export default Logins;


