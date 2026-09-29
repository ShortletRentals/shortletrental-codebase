import React, { useState } from 'react';
import {
  View,
  Image,
  Text,
  ImageBackground,
  TouchableOpacity,
  _Text,
  ScrollView,
  Linking,
  TextInput,
  ActivityIndicator,
  Platform,
} from 'react-native';
import { color, height, width } from '../../styles/colors';
import Images from '../../styles/Images';
import { commonStyles } from './../../styles/style';
import { Colors, colors } from 'react-native/Libraries/NewAppScreen';
import Header from '../../components/Header';
import TextView from '../../components/TextView';
import axios from 'axios';
import AsyncStorage from '@react-native-async-storage/async-storage';
import { useEffect } from 'react';
import { useIsFocused } from '@react-navigation/native';
import { GoogleSocialLogin, Send_Otp, Sign_UP } from '../../network/Webconstant';
import { useContext } from 'react';
import FlashMessage, { showMessage } from 'react-native-flash-message';
import { customToast } from '../../components/ToastMessage';
import { Toast } from 'react-native-toast-message/lib/src/Toast';
import { GoogleSignin, statusCodes } from '@react-native-google-signin/google-signin';
import { onFacebookLogin } from '../../components/FacebookLogin';
import { Context as AuthContext } from '../../context/AuthContext';
import CountryPicker from 'react-native-country-picker-modal'
import ReactNativeModal from 'react-native-modal';
import { ValidationRegex } from '../../validations/ValidationRegex';
import { KeyboardAwareView } from 'react-native-keyboard-aware-view';
import messaging from '@react-native-firebase/messaging';

const SignUp = props => {
  const [name, setName] = useState('');
  const [mobileNumber, setMobileNumber] = useState('');
  const [emailAddrss, setEmailAddrss] = useState('');
  const [password, setPassword] = useState('');
  // const [passwordWarn, setPasswordWarn] = useState('');
  const [conformPassword, setConformPassword] = useState('');
  const [isSelect, setIsSelect] = useState(true);
  const [select, setSelect] = useState(true);
  const [showLoader, setShowLoader] = useState(false);
  const [Mobile, setMoblie] = useState('')
  const [Otp, setOtp] = useState('')
  const [loader, setLoader] = useState(false)
  const [countryCode, setCountryCode] = useState('NG')
  const [visible, setVisible] = useState(false)
  const [country, setCountry] = useState('234')
  const [token, setToken] = useState('');

  const [withCountryNameButton, setWithCountryNameButton] = useState(
    false,
  )

  const isfocus = useIsFocused();
  const storeUser = async () => {
    try {
      await AsyncStorage.setItem('username', name);
      await AsyncStorage.setItem('mobile', mobileNumber);
      await AsyncStorage.setItem('user_email', emailAddrss);
      await AsyncStorage.setItem('password', password);
      await AsyncStorage.setItem('confirmPassword', conformPassword);

      // nameData()
      getData();
    } catch (error) {
      console.log(error);
    }
  };

  const { state: { Token_ID } } = useContext(AuthContext)

  useEffect(() => {
    checkToken()
    storeUser();
    GoogleSignin.configure({
      webClientId: ""
    })
  }, []);

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
      ).catch((e) => {
        console.log('googlesign', e);
      })
    } catch (error) {
      if (error.code === statusCodes.SIGN_IN_CANCELLED) {
        console.log('sign in cancelled', error);
        // user cancelled the login flow
      } else if (error.code === statusCodes.IN_PROGRESS) {
        // operation (e.g. sign in) is in progress already
        console.log('in progress', error);
      } else if (error.code === statusCodes.PLAY_SERVICES_NOT_AVAILABLE) {
        // play services not available or outdated
        console.log('play services not available', error);
      } else {
        // some other error happened
        console.log('error in end', error);
      }
    }
  }


  const onSubmitFormHandler = () => {
  // let  regex =/^[A-Za-z\s]{1,}[\.]{0,1}[A-Za-z\s]{2,15}$/;

    if (name.trim() == '') {
      Toast.show({
        type: 'error',
        text1: 'Please enter full name',
      });
    } 
    else if (country == '') {
      Toast.show({
        type: 'error',
        text1: 'Please select country code',
      });
    }
    else if (mobileNumber.trim() == '') {
      Toast.show({
        type: 'error',
        text1: 'Please eneter your mobile no.',
      });
    }
    // else if (mobileNumber.length < 8 ) {
    //   Toast.show({
    //     type: 'error',
    //     text1: 'Please enter minimum 8 digit mobile number',
    //   });
    // } 
    else if (mobileNumber !== '') {
      let re = /^([+]\d{2})?\d{8,12}$/;
      if (re.test(mobileNumber) === false) {
        Toast.show({
          type: 'error',
          text1: 'please enter 8-12 digit mobile number',
        });
      } else if (emailAddrss.trim() == '') {
        Toast.show({
          type: 'error',
          text1: 'Please enter email address',
        });
      } else if (emailAddrss !== '') {
        let reg =
          /^(([^<>()[\]\.,;:\s@\"]+(\.[^<>()[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/i;
        // let reg = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
        if (reg.test(emailAddrss) === false) {
          Toast.show({
            type: 'error',
            text1: 'Invalid e-mail address.',
          });
        } else if (password.trim() == '') {
          Toast.show({
            type: 'error',
            text1: 'Please enter password',
          });
        }

        else if (password !== '') {
          var regularExpression = /^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#\$%\^&\*])(?=.{8,15})/
          //  /^[@#][A-Za-z0-9]{8,13}$/
          // /^[A-Z][@#][A-Za-z0-9]{8,16}$/
          // /^[a-zA-Z0-9!@#$%^&*]{8,16}$/;
          if (regularExpression.test(password) === false) {
            Toast.show({
              type: 'error',
              text1: 'Your password must contain at least (1) lowercase',
              text2:' (1) uppercase letter, (1) special character and minimum 8 characters..'
            });
          } else if (conformPassword == '') {
            Toast.show({
              type: 'error',
              text1: 'Please enter confirm password',
            });
          }
          else if (password != conformPassword) {
            Toast.show({
              type: 'error',
              text1: 'password not match',
            });
          } else if (regularExpression.test(conformPassword) === false) {
            Toast.show({
              type: 'error',
              text1: 'Password field should not be less than 8 characters.',
            });
          }
          else {
            handleSignup();
          }
        }
      }
    }
  }
  const onSelect = (country) => {
    setCountryCode(country.cca2)
    setCountry(country.callingCode[0])
  }

  const getData = async () => {
    const name = await AsyncStorage.getItem('username');
    // console.log('===n', name);
  };
  const {
    signUpUser,
    sendOtpHandler,
    state: { activityIndicator, Password, country_code },
  } = useContext(AuthContext);

  // const sendOtp = () => {

  //   let  data = {
  //         type: 'register',
  //         country_code,
  //         Mobile

  //     }
  //     sendOtpHandler(data,()=>{
  //         //  props.navigation.navigate('Otp', { paramsotp: { mobile: mobileNumber, country_code: country_code } })
  //     })
  // }
  // console.log('country',country);
  const handleSignup = async () => {
    console.log('signup screen');
    setLoader(true)
    try {
      await axios({
        method: 'post',
        url: Send_Otp,
        data: {
          type: 'register',
          country_code: country,
          mobile: mobileNumber,
          email: emailAddrss,
          full_name:name
        }
      })
        .then(function (response) {
          console.log("API Response signup -----", response);
          if (response.data.status === true) {
            // setMoblie(response.data.data.mobile)
            setOtp(response.data.data)
            Toast.show({
              type: 'success',
              text1: 'Shortlet',
              text2: response.data.message,
            });
            setName('');
            setConformPassword('');
            setEmailAddrss('');
            setMobileNumber('');
            setPassword('');
            setLoader(false)
            props?.navigation?.navigate('Otp', { from: 'SignUp', MOBILE: mobileNumber, EMAIL: emailAddrss, NAME: name, PASSWORD: password, CONFIRMPASSWORD: conformPassword, OTP: response.data.data, COUNTRYCODE: country });
          }
          else {
            Toast.show({
              type: 'error',
              text1: 'Shortlet',
              text2: response.data.message || response.data.message.email,
            });
            setLoader(false)
          }
        })
    } catch (error) {
      console.log('error is signup', error);
      Toast.show({type:'error',text1:'Technial Error'})
      setLoader(false)
    }

  };

  const onClickFacebookLoginHandler = async () => {
    onFacebookLogin().then(async (data, token) => {
      console.log('facebook login', data);
    })
  }

  const onInputChange = (value) => {

    console.log('Input value: ', value);
    const re = /^[a-zA-Z\s]*$/;
    if (value === "" || re.test(value)) {
      setName(value);
    }
  }
  return (
    <View style={commonStyles.container}>
      <Header
        props={props}
        Heading={'Sign Up'}
        onPress={() => props.navigation.goBack()}
      />
      <FlashMessage position={'center'} />
      <KeyboardAwareView>
      <ScrollView showsVerticalScrollIndicator={false}>
        <Image
          source={Images.mobileIcon}
          style={{
            width: width / 2,
            height: height / 3.8,
            resizeMode: 'stretch',
            alignSelf: 'center',
            marginTop: 10,
          }}
        />
        {/* <Text>{JSON.stringify(name)}</Text> */}
        <TextView
          placeholder={'Full Name'}
          value={name}
          onChangeText={onInputChange}
        />

        <View style={{ flexDirection: 'row', justifyContent: 'flex-start', alignItems: 'center', marginHorizontal: 20, }}>
          <View style={{backgroundColor:color.appTextBackgoundColor,flexDirection:'row',padding:10,borderRadius:8,alignItems:'center'}}>
          <CountryPicker
            {...{
              onSelect,
              // withCountryNameButton
            }}
            visible={visible}
            countryCode={countryCode}
            withFilter={true}
            withFlag={false}
            // withFlagButton={'In'}
            // withEmoji={false}
            // withCountryNameButton
            // withCallingCodeButton
            // withCallingCode='91'
            withAlphaFilter
            
          />

          <Text style={{color:'#000'}}>+{country}</Text>
          </View>
          <TextInput
            placeholder={'Mobile Number'}
            value={mobileNumber}
            onChangeText={text => {
              setMobileNumber(text.replace(/[^0-9]/g, ''));
              // setState({ ...state, mobileInput: text.replace(/[^0-9]/g, ''),  });
            }}

            // onChangeText={(text) => setMobileNumber(text)}
            // isNumeric={true}
            keyboardType='numeric'
            maxLength={12}
            style={{
              height: 50,
              width: '70%',
              margin: 10,
              backgroundColor: color.appTextBackgoundColor,
              borderRadius: 10,
              paddingHorizontal: 14,
              fontSize: 15,color:'#000'
            }}placeholderTextColor={color.appTextColor}
          />


        </View>
        <TextInput
          placeholder={'Email Address'}
          value={emailAddrss}
          onChangeText={e => setEmailAddrss(e.replace(/[^A-Za-z0-9-@A-Za-z0-9.A-Za-z0-9]/g, ""))}
          style={{
            paddingLeft: 15,
            height: 50,
            margin: 10,
            backgroundColor: color.appTextBackgoundColor,
            borderRadius: 10,
            marginLeft: 20,
            marginRight: 20,
            fontSize: 15,color:'#000'
          }}placeholderTextColor={color.appTextColor}
          keyboardType='email-address'
        />
        <View
          style={{
            marginTop: 20,
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
            value={password}
            onChangeText={e => setPassword(e)}
            secureTextEntry={isSelect}
            style={{
              // paddingLeft: 15,
              height: 50,
              width: '80%',
              margin: 10,
              backgroundColor: color.appTextBackgoundColor,
              borderRadius: 10,
              // marginLeft: 20,
              // marginRight: 20,
              fontSize: 15,color:'#000'
            }}placeholderTextColor={color.appTextColor}
          // secureTextEntry={isSelect}
          />
          <TouchableOpacity onPress={() => setIsSelect(!isSelect)}>
            <Image
              source={isSelect ? Images.hiddenIcon : Images.eyeIcon}
              style={{ height: 20, width: 20, marginRight: 20 }}
            />
          </TouchableOpacity>
        </View>
        <View
          style={{
            marginTop: 20,
            flexDirection: 'row',
            justifyContent: 'space-between',
            alignItems: 'center',
            height: 50,
            backgroundColor: color.appTextBackgoundColor,
            marginHorizontal: 20,
            borderRadius: 10,
          }}>
          <TextInput
            placeholder={'Confirm Password'}
            value={conformPassword}
            onChangeText={e => setConformPassword(e)}
            secureTextEntry={select}
            style={{
              // paddingLeft: 15,
              height: 50,
              width: '80%',
              margin: 10,
              backgroundColor: color.appTextBackgoundColor,
              borderRadius: 10,
              // marginLeft: 20,
              // marginRight: 20,
              fontSize: 15,color:'#000'
            }}placeholderTextColor={color.appTextColor}
          // secureTextEntry={isSelect}
          />
          <TouchableOpacity onPress={() => setSelect(!select)}>
            <Image
              source={select ? Images.hiddenIcon : Images.eyeIcon}
              style={{ height: 20, width: 20, marginRight: 20 }}
            />
          </TouchableOpacity>
        </View>

        <TouchableOpacity
          // onPress={onSubmitFormHandler}
          activeOpacity={0.5}
          onPress={() => {
            onSubmitFormHandler()
          }}
          style={{
            marginTop: 20,
            backgroundColor: color.appOrangeColor,
            width: '90%',
            height: 50,
            // padding: 10,
            borderRadius: 10,
            justifyContent: 'center',
            // marginLeft: 15,
            marginHorizontal: 20,
            height: 50,
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
              fontSize: 16,
              color: color.appWhiteColor,
            }}>
            Sign Up
          </Text>
        </TouchableOpacity>
        {
          loader ? <View style={{ top: -35, right: -55 }}><ActivityIndicator size='small' color='#fff' /></View> : null
        }

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
            justifyContent:'flex-start',
            alignItems:'center',
            height: 40,
            alignSelf: 'center',
            marginTop: 15,
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
       
        <View
          style={{ flexDirection: 'row', alignSelf: 'center', marginTop: 10, }}>
          <Text style={{color:'#000'}}>If you already have an account? </Text>
          <TouchableOpacity onPress={() => props.navigation.navigate('Logins')}>
            <Text style={{ color: color.appYellowColor }}>Login</Text>
          </TouchableOpacity>
        </View>
        <View style={{ marginBottom: 20 }} />
      </ScrollView>
      </KeyboardAwareView>
    </View>
  );
};

export default SignUp;
