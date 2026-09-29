import React, { useState, useEffect, useContext } from 'react';
import CheckBox from '@react-native-community/checkbox';
import {
  View,
  Image,
  Text,
  ImageBackground,
  TouchableOpacity,
  _Text,
  ScrollView,
  Modal,
  StyleSheet,
  ActivityIndicator,
  Linking,
  Platform,
} from 'react-native';
import { color, height, width } from '../../styles/colors';
import Images from '../../styles/Images';
import { commonStyles } from './../../styles/style';
import { Colors, colors } from 'react-native/Libraries/NewAppScreen';
import Header from '../../components/Header';
import TextView from '../../components/TextView';
import LinearGradient from 'react-native-linear-gradient';
import { Toast } from 'react-native-toast-message/lib/src/Toast';
import { Dropdown } from 'react-native-element-dropdown';
import axios from 'axios';
import { Context as BookingContext } from '../../context/BookingContext';
import { useFocusEffect, useRoute } from '@react-navigation/native';
import { Context as AuthContext } from '../../context/AuthContext';
import AsyncStorage from '@react-native-async-storage/async-storage';
import { TextInput } from 'react-native';
import CountryPicker from 'react-native-country-picker-modal';
import ReactNativeModal from 'react-native-modal';
import { launchCamera, launchImageLibrary } from 'react-native-image-picker';
import { Pressable } from 'react-native';
import { AddArea, AddCity, AddProvince, MAIN_URL, Send_Otp, getAllArea, getAllCity, getAllCountry, getAllProvince } from '../../network/Webconstant';
import { KeyboardAwareView } from 'react-native-keyboard-aware-view';

let countryId = '';
let provinceId = '';
let cityId = '';
let areaId = '';
let userId = '';
let countryName = [];

const data = [
  { label: 'Company', value: '1' },
  { label: 'Dr', value: '2' },
  { label: 'Family', value: '3' },
  { label: 'Mr & Mrs', value: '4' },
  { label: 'Mr.', value: '5' },
  { label: 'Mrs.', value: '5' },
  { label: 'Ms.', value: '5' },
  { label: 'PhD', value: '5' },
  { label: 'Prof', value: '5' },
];
const countryCode = [
  { label: '+91', value: '1' },
  { label: '+91', value: '2' },
  { label: '+91', value: '3' },
  { label: '+91', value: '4' },
  { label: '+91', value: '5' },
  { label: '+91', value: '6' },
  { label: '+91', value: '7' },
  { label: '+91', value: '8' },
  { label: '+91', value: '9' },
];

const hostingType = [
  { label: 'Individual', value: '1' },
  { label: 'Business', value: '2' },
];

const BecomeaHost = props => {
  const [value, setValue] = useState(null);
  const [countryCodeNew, setcountryCode] = useState(null);
  const [countryNameNew, setcountryName] = useState(null);
  const [province, setProvince] = useState(null);
  const [city, setCity] = useState(null);
  const [area, setArea] = useState(null);
  const [hosting, setHosting] = useState(null);
  // const [userId, setUserId] = useState(null);

  const [fullName, setFullName] = useState('');
  const [lastName, setLastName] = useState('');

  const [landmark, setLandmark] = useState('');
  const [address, setAddress] = useState('');
  const [businessName, setBusinessName] = useState('');

  const [modalVisible, setModalVisible] = useState(false);

  const [MobileNumber, SetMobileNumber] = useState('');
  const [City, SetCity] = useState('');
  const [Country, SetCountry] = useState('');
  const [Email, SetEmail] = useState('');
  const [ConfirmEmailAddress, SetConfirmEmailAddress] = useState('');
  const [Password, SetPassword] = useState('');
  const [ConfirmPassword, SetConfirmPassword] = useState('');
  const [AreYouAgency, SetAreYouAgency] = useState('');
  const [toggleCheckBox, setToggleCheckBox] = useState(false);
  const [toggleCheckBox1, setToggleCheckBox1] = useState(false);
  const [toggleMale, setToggleMale] = useState(false);
  const [toggleFemale, setToggleFemale] = useState(true);
  const [value1, setValue1] = useState('Male');
  const [loader, setLoader] = useState(false);
  const [cityLoader, setCityLoader] = useState(false);
  const [countryLoader, setCountryLoader] = useState(false);

  const [areaLoader, setAreaLoader] = useState(false);

  const [isSelect, setIsSelect] = useState(true);
  const [select, setSelect] = useState(true);
  const [country, setCountry] = useState('234');
  const [countryCode, setCountryCode] = useState('NG');
  const [visible, setVisible] = useState(false);
  const [about, setAbout] = useState('');
  const [modalVisible1, setModalVisible1] = useState(false);
  const [imageUri, setImageUri] = useState('');
  const [provinceModal, setProvinceModal] = useState(false)
  const [cityModal, setCityModal] = useState(false)
  const [areaModal, setAreaModal] = useState(false)
  const [provinceName, setProvinceName] = useState('')
  const [cityName, setCityName] = useState('')
  const [areaName, setAreaName] = useState('')
  const [bookingData, setBookingData] = useState([])
  const [provinceData, setProvinceData] = useState([])
  const [cityData, setCityData] = useState([])
  const [areaData, setAreaData] = useState([])


  const {
    // getAllCountryApi,
    getAreaApi,
    updateBookingData,
    // getProvinceApi,
    getCityApi,
    getAllCountryCodeApi,
    getProvinceApiModal,
    getAreaApiModal,
    getCityApiModal,
    state: {
      // bookingData,
      activityIndicator,
      // provinceData,
      // cityData,
      // areaData,
      countryData,
    },
  } = useContext(BookingContext);

  // console.log('================country code new ', provinceName, cityName, areaName, cityId,);
  const handleValidation = () => {
    if (value == null) {
      Toast.show({
        type: 'error',
        text1: 'Shortlet',
        text2: 'Please select title',
      });
    } else if (fullName == '') {
      Toast.show({
        type: 'error',
        text1: 'Shortlet',
        text2: 'Please enter first name',
      });
    } else if (lastName == '') {
      Toast.show({
        type: 'error',
        text1: 'Shortlet',
        text2: 'Please enter last name',
      });
    } else if (value1 == '') {
      Toast.show({
        type: 'error',
        text1: 'Shortlet',
        text2: 'Please select gender',
      });
    } else if (Email == '') {
      Toast.show({
        type: 'error',
        text1: 'Shortlet',
        text2: 'Please enter e-mail address',
      });
    } else if (Email !== '') {
      let reg =/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
        // /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
      if (reg.test(Email) === false) {
        Toast.show({
          type: 'error',
          text1: 'Shortlet',
          text2: 'Invalid e-mail address',
        });
      } else if (country == '') {
        Toast.show({
          type: 'error',
          text1: 'Shortlet',
          text2: 'Please select country',
        });
      } else if (MobileNumber == '') {
        Toast.show({
          type: 'error',
          text1: 'Shortlet',
          text2: 'Please enter mobile number',
        });
      } else if (MobileNumber.length < 8) {
        Toast.show({
          type: 'error',
          text1: 'Shortlet',
          text2: 'Please enter valid mobile number',
        });
      } else if (Password == '') {
        Toast.show({
          type: 'error',
          text1: 'Shortlet',
          text2: 'Please enter password',
        });
      }
      else if (Password !== '') {
        var regularExpression = /^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#\$%\^&\*])(?=.{8,15})/

        if (regularExpression.test(Password) === false) {
          Toast.show({
            type: 'error',
            text1: 'Your password must contain at least (1) lowercase, (1)',
            text2: ' uppercase letter, (1) special character and minimum 8 characters.'
          })
          // alert(' ')
        }
        // else if (Password.length < 6) {
        //   Toast.show({
        //     type: 'error',
        //     text1: 'Shortlet',
        //     text2: 'Password field should not be less than 6 characters.',
        //   });
        // }
        else if (ConfirmPassword == '') {
          Toast.show({
            type: 'error',
            text1: 'Shortlet',
            text2: 'Please enter confirm password.',
          });
        } else if (Password != ConfirmPassword) {
          Toast.show({
            type: 'error',
            text1: 'Shortlet',
            text2: 'password not match.',
          });
        } else if (about == '') {
          Toast.show({
            type: 'error',
            text1: 'Shortlet',
            text2: 'please enter about us',
          });
        } else if (countryNameNew == null) {
          Toast.show({
            type: 'error',
            text1: 'Shortlet',
            text2: 'Please select country name',
          });
        } else if (province == null) {
          Toast.show({
            type: 'error',
            text1: 'Shortlet',
            text2: 'Please select province',
          });
        } else if (city == null) {
          Toast.show({
            type: 'error',
            text1: 'Shortlet',
            text2: 'Please select city',
          });
        } else if (area == null) {
          Toast.show({
            type: 'error',
            text1: 'Shortlet',
            text2: 'Please select area',
          });
        } else if (toggleCheckBox == false) {
          Toast.show({
            type: 'error',
            text1: 'Shortlet',
            text2: 'Please Agree Privacy Policy',
          });
        } else if (toggleCheckBox1 == false) {
          Toast.show({
            type: 'error',
            text1: 'Shortlet',
            text2: 'Please agree to recieve commercial information',
          });
        } else {
          becomeAHostSignin();
          //    props.navigation.navigate("Otp",{type:1})
        }
      }
    }
  };



  useFocusEffect(
    React.useCallback(() => {
      getAllCountryApi();
      getAllCountryCodeApi();
    }, [props]),
  );
  const getAllCountryApi = async () => {
    try {
      axios({
        method: 'get',
        url: getAllCountry,
        // data,
      })
        .then(function (response) {
          if (response.data.status === true) {
            // console.log('====res country', response.data.data);
            let arr = []
            response.data.data.map(item => {
              arr.push({ value: item.id, label: item.name });
            });
            setBookingData(arr)
          }
        })
        .catch(error => console.log('something went wrong ', error));
      // dispatch({ type: 'loadActivityIndicator' });
    } catch (error) {
      // dispatch({ type: 'loadActivityIndicator' });
      console.log('eerrr', error);
    }
  };
  const onSelect = country => {
    setCountryCode(country.cca2);
    setCountry(country.callingCode[0]);
  };
  // console.log("country code =====",countryData)
  const storeUser = async () => {
    try {
      // let data = {
      //  userId
      // };
      await AsyncStorage.setItem('user_id', JSON.stringify(userId));
      // alert("jjjjj")
    } catch (error) {
      console.log(error);
    }
  };
  const Newdata = {
    title: value,
    fullname: fullName,
    surname: lastName,
    email: Email,
    country_code: country,
    mobile: MobileNumber,
    password: Password,
    hear_about_us: about,
    country_id: countryId,
    province_id: provinceId,
    city_id: cityId,
    area: areaId,
    landmark: landmark,
    address: address,
    hosting_type: hosting,
    business_name: businessName,
    gender: value1,
    business_registration_image: imageUri,
  };
  const becomeAHostSignin = () => {
    let data = {
      type: 'register',
      country_code: country,
      mobile: MobileNumber,
      email: Email,
      full_name:fullName
    };
    try {
      setLoader(true);
      axios({
        method: 'post',
        url: Send_Otp,
        data,
        // headers: { "content-type": "application/x-www-form-urlencoded" }
      }).then(async function (response) {
        // console.log("API Response Login -----", response.data);

        if (response.data.status == true) {
          // console.log('called true======================', response);
          userId = response.data.data.id;
          // AsyncStorage.setItem('USER',response.data.data.id)

          Toast.show({
            type: 'success',
            text1: 'Shortlet',
            text2: response.data.message,
          });
          storeUser();
          setLoader(false);
          props.navigation.navigate('OtpHost', {
            type: 1,
            id: userId,
            Newdata: Newdata,
            sName: 'BecomeHost',
            MOBILE: MobileNumber,
            EMAIL:Email
          });
        } else {
          console.log('called false');

          Toast.show({
            type: 'error',
            text1: 'Shortlet',
            text2: response.data.message,
          });
          setLoader(false);
        }
      });
    } catch (error) {
      console.log(error);
      Toast.show({
        type: 'error',
        text1: 'Technical Error',
        // text2: response.data.message,
      });
      setLoader(false);
    }
    finally{
      setLoader(false);

    }
  };

  // const getProvinceApi = dispatch => async data => {
  //   try {
  //     axios({
  //       method: 'post',
  //       url: getAllProvince,
  //       data,
  //     }).then(function (response) {
  //       if (response.data.status === true) {
  //         console.log('====res province', response.data.data);
  //         let temp = response.data.data.map(item => {
  //           return { value: item.id, label: item.name };
  //         });
  //         // dispatch({ type: 'updateProvince', payload: temp });
  //       }
  //     });
  //   } catch (error) {
  //     console.log('eerrr', error);
  //   }
  // };

  const getProvince = () => {
    // setCountryLoader(true)
    try {
      axios({
        method: 'post',
        url: getAllProvince,
        data: { country_id: countryId, province_id: provinceId }
      }).then(function (response) {
        if (response.data.status === true) {
          // console.log('====res province', response.data.data);
          let arr = []
          response.data.data.map(item => {
            arr.push({ value: item.id, label: item.name });
          });
          setProvinceData(arr)
          // setCountryLoader(false)
        }
      });
    } catch (error) {
      console.log('eerrr', error);
      // setCountryLoader(false)
    }
  };


  const getProvinceModalApi = () => {
    try {
      axios({
        method: 'post',
        url: AddProvince,
        data: { country_id: countryId, name: provinceName }
      }).then(function (response) {
        // console.log('====res province modal-----------', response.data.status);
        if (response.data.status === true) {
          Toast.show({
            type: 'success',
            text1: 'Shortlet',
            text2: response.data.message
          })

          // console.log('---------------status-----------');
          // setProvinceData(response.data.data)
          setProvinceModal(false)
          setProvinceName('')
          getAllCountryApi()
        } else {
          Toast.show({
            type: 'error',
            text1: 'Shortlet',
            text2: response.data.message
          })
        }
      });
    } catch (error) {
      console.log('eerrr', error);

    }
  };

  const getCity = () => {
    // setCityLoader(true)
    try {
      axios({
        method: 'post',
        url: getAllCity,
        data: { country_id: countryId, province_id: provinceId }
      }).then(function (response) {
        if (response.data.status === true) {
          // console.log('====res city', response.data.data);
          let arr = []
          response.data.data.map(item => {
            arr.push({ value: item.id, label: item.name });
          });
          setCityData(arr)
          // setCityLoader(false)
        }
      });
    } catch (error) {
      console.log('eerrr', error);
      // setCityLoader(false)
    }
  };

  const getCityModalApi = () => {
    try {
      axios({
        method: 'post',
        url: AddCity,
        data: { country_id: countryId, name: cityName, province_id: provinceId }
      }).then(function (response) {
        // console.log('====res city', response.data.status);
        if (response.data.status === true) {
          Toast.show({
            type: 'success',
            text1: 'Shortlet',
            text2: response.data.message
          })
          // let temp =[]
          //  response.data.data.map(item => {
          //   temp.push({ value: item.id, label: item.name });
          // });
          // setCityData(temp)
          setCityModal(false)
          setCityName('')
          getAllCountryApi()
          getCity()
        } else {
          Toast.show({
            type: 'error',
            text1: 'Shortlet',
            text2: response.data.message
          })
        }
      });
    } catch (error) {
      console.log('eerrr', error);
    }
  };


  const getArea = () => {
    // setAreaLoader(true)
    try {
      axios({
        method: 'post',
        url: getAllArea,
        // data,
        data: { country_id: countryId, province_id: provinceId, city_id: cityId }

      }).then(function (response) {
        if (response.data.status === true) {
          // console.log('====res city', response.data.data);
          let arr = []
          response.data.data.map(item => {
            arr.push({ value: item.id, label: item.name });
          });
          setAreaData(arr)
          // setAreaLoader(false)
        }
      });
    } catch (error) {
      console.log('eerrr', error);
      // setAreaLoader(false)
    }
  };
console.log('area data',areaData);
  const getAreaModalApi = () => {
    try {
      axios({
        method: 'post',
        url: AddArea,
        data: { country_id: countryId, name: areaName, province_id: provinceId, city_id: cityId }
      }).then(function (response) {
        if (response.data.status === true) {
          // console.log('====res area', response.data.data);
          Toast.show({
            type: 'success',
            text1: 'Shortlet',
            text2: response.data.message
          })
          setAreaModal(false)
          getCity()
          getArea()
          getAllCountryApi()
          setAreaName('')
        } else {
          Toast.show({
            type: 'error',
            text1: 'Shortlet',
            text2: response.data.message
          })
        }
      });
    } catch (error) {
      console.log('eerrr', error);
    }
  };

  const CameraAction = () => {
    let options = {
      storageOption: {
        path: 'images',
        mediaType: 'photo',
      },
      includeBase64: true,
    };
    launchCamera(options, response => {
      console.log('Response =', response);
      if (response.didCancel) {
        console.log('User cancelled image picker');
      } else if (response.error) {
        console.log('ImagePicker Error:', response.error);
      } else if (response.CustomButton) {
        console.log('User tapped custom button:', response.CustomButton);
      } else {
        const source = {
          uri: 'data:image/jpeg;base64,' + response?.assets[0].base64,
        };
        // onClose(source)
        // setImageUri(source)
        setImageUri(response?.assets[0]);
        setModalVisible1(false);
      }
    });
  };

  const GalleryAction = () => {
    let options = {
      storageOption: {
        path: 'images',
        mediaType: 'photo',
      },
      includeBase64: true,
    };
    launchImageLibrary(options, response => {
      console.log('Response =', response);
      if (response.didCancel) {
        console.log('User cancelled image picker');
      } else if (response.error) {
        console.log('ImagePicker Error:', response.error);
      } else if (response.CustomButton) {
        console.log('User tapped custom button:', response.CustomButton);
      } else {
        const source = {
          uri: 'data:image/jpeg;base64,' + response?.assets[0]?.base64,
        };
        // onClose(source)
        // setImageUri(source)
        setImageUri(response?.assets[0]);
        setModalVisible1(false);
      }
    });
  };
  return (
    <KeyboardAwareView style={commonStyles.container}>
      <Header
        props={props}
        Heading={'Become a Host'}
        onPress={() => props.navigation.goBack()}
      />
      {/* <KeyboardAwareView> */}
      <ScrollView contentContainerStyle={{ flexGrow: 1, marginTop: 10 }}>
        <Text style={{ marginHorizontal: 20, color: '#000', fontSize: 16 }}>
          Personal information*
        </Text>
        <Text
          style={{
            marginHorizontal: 20,
            color: '#000',
            fontSize: 16,
            marginTop: 5,
          }}>
          Title*
        </Text>
        <Dropdown
          style={{
            // height:40,
            // marginHorizontal:20,
            paddingLeft: 15,
            height: 50,
            margin: 10,
            backgroundColor: color.appTextBackgoundColor,
            borderRadius: 10,
            marginLeft: 20,
            marginRight: 20,
            fontSize: 15,
            paddingRight: 15,
          }}
          // placeholderStyle={styles.placeholderStyle}
          // selectedTextStyle={styles.selectedTextStyle}
          // inputSearchStyle={styles.inputSearchStyle}
          // iconStyle={styles.iconStyle}
          placeholderStyle={{ color: '#000' }}
          itemTextStyle={{ color: '#000' }}
          selectedTextProps={{ style: { color: '#000' } }}
          data={data}
          search={false}
          maxHeight={300}
          labelField="label"
          valueField="value"
          placeholder="Select Title"
          // searchPlaceholder="5 guests"
          value={data}
          onChange={item => {
            setValue(item.label);
          }}
        />
        <Text style={{ marginHorizontal: 20, color: '#000', fontSize: 16 }}>
          First Name*
        </Text>
        <TextView
          placeholder={'First Name'}
          value={fullName}
          onChangeText={e => setFullName(e)}
          isNumeric={false}
        />
        <Text style={{ marginHorizontal: 20, color: '#000', fontSize: 16 }}>
          Last Name*
        </Text>
        <TextView
          placeholder={'Last Name'}
          value={lastName}
          onChangeText={e => setLastName(e)}
          isNumeric={false}
        />
        <Text style={{ marginHorizontal: 20, color: '#000', fontSize: 16 }}>
          Gender*
        </Text>
        <View
          style={{
            flexDirection: 'row',
            backgroundColor: color.appTextBackgoundColor,
            marginHorizontal: 20,
            borderRadius: 8,
            alignItems: 'center',
            padding: 4,
            marginVertical: 10,
          }}>
          <View
            style={{
              flexDirection: 'row',
              marginTop: 0,
              height: 40,
              marginLeft: 8,
              alignItems: 'center',
            }}>
            <TouchableOpacity
              onPress={() => [
                setToggleFemale(true),
                setToggleMale(false),
                setValue1(toggleMale ? 'Male' : 'Female'),
              ]}>
              <Image
                source={toggleFemale ? Images.selectedIcons : Images.whiteDot}
                style={{ height: 20, width: 20 }}
              />
            </TouchableOpacity>
            <Text style={{ paddingLeft: 10, paddingRight: 10, fontSize: 15, color: '#000' }}>
              Male
            </Text>
          </View>
          <View style={{ flexDirection: 'row', marginTop: 0 }}>
            <TouchableOpacity
              onPress={() => [
                setToggleMale(true),
                setToggleFemale(false),
                setValue1(toggleFemale ? 'Female' : 'Male'),
              ]}>
              <Image
                source={toggleMale ? Images.selectedIcons : Images.whiteDot}
                style={{ height: 20, width: 20 }}
              />
            </TouchableOpacity>
            <Text style={{ paddingLeft: 10, paddingRight: 10, fontSize: 15, color: '#000' }}>
              Female
            </Text>
          </View>
        </View>
        <Text style={{ marginHorizontal: 20, color: '#000', fontSize: 16 }}>
          Email*
        </Text>
        <TextView
          placeholder={'Email Address'}
          value={Email}
          onChangeText={e => SetEmail(e)}
          isNumeric={false}
        />

        {/* <Text style={{marginHorizontal:20,color:'#000',fontSize:16}}>Email*</Text>
        <TextView
        placeholder={'Confirm Email Address'}
          value={ConfirmEmailAddress}
          onChangeText={e => SetConfirmEmailAddress(e)}
          isNumeric={false}
        /> */}

        <Text style={{ marginHorizontal: 20, color: '#000', fontSize: 16 }}>
          Phone Number*
        </Text>
        <View
          style={{
            flexDirection: 'row',
            alignItems: 'center',
            justifyContent: 'center',
            marginVertical: 5,
            // marginRight: 10,
          }}>
          <View
            style={{
              flexDirection: 'row',
              alignItems: 'center',
              backgroundColor: color.appTextBackgoundColor,
              padding: 8,
              borderRadius: 8,
              // marginHorizontal: 10,
            }}>
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
          {/* <Dropdown
          style={{
            // height:40,
            // marginHorizontal:20,
            paddingLeft: 15,
            height: 50,
            margin: 10,
            backgroundColor: color.appTextBackgoundColor,
            borderRadius: 10,
            marginLeft: 20,
            marginRight: 20,
            fontSize: 15,
            paddingRight: 15,
          }}
          // placeholderStyle={styles.placeholderStyle}
          // selectedTextStyle={styles.selectedTextStyle}
          // inputSearchStyle={styles.inputSearchStyle}
          // iconStyle={styles.iconStyle}
          data={countryData.country}
          search={false}
          maxHeight={300}
          labelField="label"
          valueField="value"
          placeholder="Country Code"
          // searchPlaceholder="5 guests"
          value={countryCode.label}
          onChange={item => {
            console.log('===item',item.label,item.value)
            setcountryCode(item.label);
          }}
          />
        <Text style={{marginHorizontal:20,color:'#000',fontSize:16}}>Phone Number*</Text> */}

          <TextInput
            placeholder={'Mobile Number'}
            value={MobileNumber}
            onChangeText={e => SetMobileNumber(e.replace(/[^0-9]/g, ''))}
            // isNumeric={true}
            maxLength={15}

            keyboardType="numeric"
            style={{
              height: 50,
              width: '60%',
              // margin: 10,
              // marginTop: 10,
              backgroundColor: color.appTextBackgoundColor,
              borderRadius: 10,
              // paddingHorizontal: 14,
              marginLeft:20,
              fontSize: 15, color: '#000',

              padding:8
            }}
            placeholderTextColor={color.appTextColor}
          />
        </View>

        <Text style={{ marginHorizontal: 20, color: '#000', fontSize: 16 }}>
          Password*
        </Text>
        <View
          style={{
            marginTop: 10,
            flexDirection: 'row',
            justifyContent: 'space-between',
            alignItems: 'center',
            height: 50,
            backgroundColor: color.appTextBackgoundColor,
            marginHorizontal: 20,
            borderRadius: 10,
            marginBottom: 10,
          }}>
          <TextInput
            placeholder={'Password'}
            value={Password}
            onChangeText={e => SetPassword(e)}
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
              fontSize: 15, color: '#000'
            }}
            placeholderTextColor={color.appTextColor}
          // secureTextEntry={isSelect}
          />
          <TouchableOpacity onPress={() => setIsSelect(!isSelect)}>
            <Image
              source={isSelect ? Images.hiddenIcon : Images.eyeIcon}
              style={{ height: 20, width: 20, marginRight: 20 }}
            />
          </TouchableOpacity>
        </View>
        <Text style={{ marginHorizontal: 20, color: '#000', fontSize: 16 }}>
          Confirm Password*
        </Text>
        <View
          style={{
            marginTop: 10,
            flexDirection: 'row',
            justifyContent: 'space-between',
            alignItems: 'center',
            height: 50,
            backgroundColor: color.appTextBackgoundColor,
            marginHorizontal: 20,
            borderRadius: 10,
            marginBottom: 10,
          }}>
          <TextInput
            placeholder={'Confirm Password'}
            value={ConfirmPassword}
            onChangeText={e => SetConfirmPassword(e)}
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
              fontSize: 15, color: '#000'
            }} placeholderTextColor={color.appTextColor}
          // secureTextEntry={isSelect}
          />
          <TouchableOpacity onPress={() => setSelect(!select)}>
            <Image
              source={select ? Images.hiddenIcon : Images.eyeIcon}
              style={{ height: 20, width: 20, marginRight: 20 }}
            />
          </TouchableOpacity>
        </View>
        <TextView
          placeholder={'How did you hear about us?'}
          value={about}
          onChangeText={text => setAbout(text)}
          // multiline
          maxLength={50}
        />
        <Text style={{ marginHorizontal: 20, color: '#000', fontSize: 16 }}>
          Country*
        </Text>
        {/* {activityIndicator ? (
          <ActivityIndicator size={'small'} color="#000" />
        ) : ( */}
        <View style={{zIndex:100}}>
          <Dropdown
            style={{
              // height:40,
              // marginHorizontal:20,
              paddingLeft: 15,
              height: 50,
              margin: 10,
              backgroundColor: color.appTextBackgoundColor,
              borderRadius: 10,
              marginLeft: 20,
              marginRight: 20,
              fontSize: 15,
              paddingRight: 15,
              

            }}
            // placeholderStyle={styles.placeholderStyle}
            // selectedTextStyle={styles.selectedTextStyle}
            // inputSearchStyle={styles.inputSearchStyle}
            // iconStyle={styles.iconStyle}
            placeholderStyle={{ color: '#000' }}
            itemTextStyle={{ color: '#000' }}
            selectedTextProps={{ style: { color: '#000' } }}
            data={bookingData || []}
            search={true}
            maxHeight={300}
            labelField="label"
            valueField="value"
            placeholder="Select Country"
            searchPlaceholder="Search Country"
            value={bookingData.label}
            onChange={item => {
              setcountryName(item.label);
              countryId = item.value;
              getProvince();
            }}
          />

        </View>
        {/* )} */}
        {/* <TextView
                    placeholder={'Country'}
                    value={Country}
                    onChangeText={(e) => SetCountry(e)}
                    isNumeric={false}
                /> */}
        <Text style={{ marginHorizontal: 20, color: '#000', fontSize: 16 }}>
          Province*
        </Text>
        {/* {provinceData.country == '' ? (
          <Text>Province not available for this selected country</Text>
        ) : ( */}
        {countryLoader ? (
          <ActivityIndicator size={'small'} color="#000" />
        ) : (
          <View style={{ flexDirection: 'row', alignItems: 'center', justifyContent: 'flex-start' }}>
            <Dropdown
              style={{
                // height:40,
                // marginHorizontal:20,
                width: '70%',
                paddingLeft: 15,
                height: 50,
                margin: 10,
                backgroundColor: color.appTextBackgoundColor,
                borderRadius: 10,
                marginLeft: 20,
                marginRight: 20,
                fontSize: 15,
                paddingRight: 15,
              }}
              // placeholderStyle={styles.placeholderStyle}
              // selectedTextStyle={styles.selectedTextStyle}
              // inputSearchStyle={styles.inputSearchStyle}
              // iconStyle={styles.iconStyle}
              placeholderStyle={{ color: '#000' }}
              itemTextStyle={{ color: '#000' }}
              selectedTextProps={{ style: { color: '#000' } }}
              data={provinceData || []}
              search={false}
              maxHeight={300}
              labelField="label"
              valueField="value"
              placeholder="Select Province"
              // searchPlaceholder="5 guests"
              value={provinceData.label}
              onChange={item => {
                setProvince(item.label);
                provinceId = item.value;
                getCity();
              }}
            />
            <TouchableOpacity onPress={() => setProvinceModal(!provinceModal)}>
              <Image source={Images.plusIcon} style={{ height: 40, width: 40 }} />
            </TouchableOpacity>
          </View>
        )}

        <View style={styles.centeredView}>
          <Modal
            animationType="slide"
            transparent={true}
            visible={provinceModal}
            onRequestClose={() => {
              Alert.alert('Modal has been closed.');
              setModalVisible(!modalVisible);
            }}>
            <View style={styles.centeredView}>
              <View style={styles.modalView}>
                <View style={{ flexDirection: 'row', justifyContent: 'space-between', alignItems: 'center', marginTop: -20 }}>
                  <Text style={{ color: '#000', marginHorizontal: 0, fontSize: 24, fontWeight: '400' }}>Add Province</Text>
                  <Pressable
                    onPress={() => setProvinceModal(false)}>
                    <Image source={Images.cancelImageIcon} style={{ height: 40, width: 40, alignSelf: 'flex-end', marginRight: 0 }} />

                  </Pressable>
                </View>
                <Text style={{ marginHorizontal: 0, color: '#000', fontSize: 16 }}>
                  Country*
                </Text>
                {/* {activityIndicator ? (
          <ActivityIndicator size={'small'} color="#000" />
        ) : (  */}
                <View>
                  <Dropdown
                    style={{
                      // height:40,
                      // marginHorizontal:20,
                      paddingLeft: 15,
                      height: 50,
                      // margin: 10,
                      backgroundColor: color.appTextBackgoundColor,
                      borderRadius: 10,
                      // marginLeft: 20,
                      // marginRight: 20,
                      fontSize: 15,
                      paddingRight: 15, marginTop: 10
                    }}
                    // placeholderStyle={styles.placeholderStyle}
                    // selectedTextStyle={styles.selectedTextStyle}
                    // inputSearchStyle={styles.inputSearchStyle}
                    // iconStyle={styles.iconStyle}
                    placeholderStyle={{ color: '#000' }}
                    itemTextStyle={{ color: '#000' }}
                    selectedTextProps={{ style: { color: '#000' } }}
                    data={bookingData || []}
                    search={true}
                    maxHeight={300}
                    labelField="label"
                    valueField="value"
                    placeholder="Select Country"
                    searchPlaceholder="Search Country"
                    value={bookingData.label}
                    onChange={item => {
                      setcountryName(item.label);
                      countryId = item.value;
                      getProvince();
                      // console.log(item.value)
                    }}
                  />

                </View>
                <View>

                  <Text style={{ marginHorizontal: 0, color: '#000', fontSize: 16, marginTop: 5 }}>Province Name*
                  </Text>

                  <TextInput placeholder={'Enter Name*'} value={provinceName} onChangeText={(text) => setProvinceName(text)} style={{
                    height: 50,
                    width: '100%',
                    // margin: 10,
                    marginTop: 10,
                    backgroundColor: color.appTextBackgoundColor,
                    borderRadius: 10,
                    paddingHorizontal: 14,
                    fontSize: 15, color: '#000'
                  }} placeholderTextColor={'#000'} />
                  <Pressable
                  // onPress={() => setProvinceModal(false)}
                  >
                    <View style={{ flexDirection: 'row', justifyContent: 'space-between', alignItems: 'center', marginTop: 10 }}>
                      <TouchableOpacity onPress={() => setProvinceModal(false)}>
                        <LinearGradient colors={[color.appYellowColor, color.appOrangeColor]} style={{ height: 40, width: 100, borderRadius: 10, marginTop: 0, alignItems: 'center', justifyContent: 'center' }}
                        //style={{flexDirection:'row',justifyContent:'space-between',alignItems:'center'}}
                        >
                          <Text style={{ marginHorizontal: 0, color: '#000', fontSize: 16, marginTop: 0, }}>Cancel</Text>
                        </LinearGradient>
                      </TouchableOpacity>
                      <TouchableOpacity onPress={() => getProvinceModalApi()}>
                        <LinearGradient colors={[color.appYellowColor, color.appOrangeColor]} style={{ height: 40, width: 100, borderRadius: 10, alignItems: 'center', marginTop: 5, justifyContent: 'center' }}>
                          <Text style={{ marginHorizontal: 0, color: '#000', fontSize: 16, marginTop: 0 }}>Save</Text>
                        </LinearGradient>
                      </TouchableOpacity>
                    </View>
                  </Pressable>
                </View>
              </View>
            </View>
          </Modal>

        </View>

        <Text style={{ marginHorizontal: 20, color: '#000', fontSize: 16 }}>
          City*
        </Text>

        {/* <TextView
                    placeholder={'City'}
                    value={City}
                    onChangeText={(e) => SetCity(e)}
                    isNumeric={false}
                /> */}
        {cityLoader ? (
          <ActivityIndicator size={'small'} color="#000" />
        ) : (
          <View style={{ flexDirection: 'row', alignItems: 'center', justifyContent: 'flex-start' }}>
            <Dropdown
              style={{
                // height:40,
                // marginHorizontal:20,
                width: '70%',
                paddingLeft: 15,
                height: 50,
                margin: 10,
                backgroundColor: color.appTextBackgoundColor,
                borderRadius: 10,
                marginLeft: 20,
                marginRight: 20,
                fontSize: 15,
                paddingRight: 15,
              }}
              // placeholderStyle={styles.placeholderStyle}
              // selectedTextStyle={styles.selectedTextStyle}
              // inputSearchStyle={styles.inputSearchStyle}
              // iconStyle={styles.iconStyle}
              placeholderStyle={{ color: '#000' }}
              itemTextStyle={{ color: '#000' }}
              selectedTextProps={{ style: { color: '#000' } }}
              data={cityData}

              search={false}
              maxHeight={300}
              labelField="label"
              valueField="value"
              placeholder="Select City"
              // searchPlaceholder="5 guests"
              value={cityData.label}
              onChange={item => {
                setCity(item.label);
                cityId = item.value;
                getArea();
              }}
            />
            <TouchableOpacity onPress={() => setCityModal(!cityModal)}>
              <Image source={Images.plusIcon} style={{ height: 40, width: 40 }} />
            </TouchableOpacity>
          </View>
        )}
        <View style={styles.centeredView}>
          <Modal
            animationType="slide"
            transparent={true}
            visible={cityModal}
            onRequestClose={() => {
              Alert.alert('Modal has been closed.');
              setCityModal(!cityModal);
            }}>
            <View style={styles.centeredView}>
              <View style={styles.modalView}>
                <View style={{ flexDirection: 'row', justifyContent: 'space-between', alignItems: 'center', marginTop: -20 }}>
                  <Text style={{ color: '#000', marginHorizontal: 0, fontSize: 24, fontWeight: '400' }}>Add City</Text>
                  <Pressable
                    onPress={() => setCityModal(false)}>
                    <Image source={Images.cancelImageIcon} style={{ height: 40, width: 40, alignSelf: 'flex-end', marginRight: 0 }} />

                  </Pressable>
                </View>
                <Text style={{ marginHorizontal: 0, color: '#000', fontSize: 16 }}>
                  Country*
                </Text>
                {/* {activityIndicator ? (
          <ActivityIndicator size={'small'} color="#000" />
        ) : (  */}
                <View>
                  <Dropdown
                    style={{
                      // height:40,
                      // marginHorizontal:20,
                      paddingLeft: 15,
                      height: 50,
                      // margin: 10,
                      backgroundColor: color.appTextBackgoundColor,
                      borderRadius: 10,
                      // marginLeft: 20,
                      // marginRight: 20,
                      fontSize: 15,
                      paddingRight: 15, marginTop: 10
                    }}
                    // placeholderStyle={styles.placeholderStyle}
                    // selectedTextStyle={styles.selectedTextStyle}
                    // inputSearchStyle={styles.inputSearchStyle}
                    // iconStyle={styles.iconStyle}
                    placeholderStyle={{ color: '#000' }}
                    itemTextStyle={{ color: '#000' }}
                    selectedTextProps={{ style: { color: '#000' } }}
                    data={bookingData}
                    search={true}
                    maxHeight={300}
                    labelField="label"
                    valueField="value"
                    placeholder="Select Country"
                    searchPlaceholder="Search Country"
                    value={bookingData.label}
                    onChange={item => {
                      setcountryName(item.label);
                      countryId = item.value;
                      getProvince();
                      // console.log(item.value)
                    }}
                  />

                </View>
                <View>
                  <View>
                    <Text style={{ marginHorizontal: 0, color: '#000', fontSize: 16 }}>
                      Province
                    </Text>
                    <Dropdown
                      style={{
                        // height:40,
                        // marginHorizontal:20,
                        width: '100%',
                        paddingLeft: 15,
                        height: 50,
                        // margin: 10,
                        marginTop: 10,
                        backgroundColor: color.appTextBackgoundColor,
                        borderRadius: 10,
                        // marginLeft: 20,
                        // marginRight: 20,
                        fontSize: 15,
                        paddingRight: 15,
                      }}
                      // placeholderStyle={styles.placeholderStyle}
                      // selectedTextStyle={styles.selectedTextStyle}
                      // inputSearchStyle={styles.inputSearchStyle}
                      // iconStyle={styles.iconStyle}
                      placeholderStyle={{ color: '#000' }}
                      itemTextStyle={{ color: '#000' }}
                      selectedTextProps={{ style: { color: '#000' } }}
                      data={provinceData}
                      search={false}
                      maxHeight={300}
                      labelField="label"
                      valueField="value"
                      placeholder="Select Province"
                      // searchPlaceholder="5 guests"
                      value={provinceData.label}
                      onChange={item => {
                        setProvince(item.label);
                        provinceId = item.value;
                        getCity();
                      }}
                    />
                  </View>

                  <Text style={{ marginHorizontal: 0, color: '#000', fontSize: 16, marginTop: 5 }}>City Name*
                  </Text>
                  <TextInput placeholder={'Enter Name*'} value={cityName} onChangeText={(text) => setCityName(text)} style={{
                    height: 50,
                    width: '100%',
                    // margin: 10,
                    marginTop: 10,
                    backgroundColor: color.appTextBackgoundColor,
                    borderRadius: 10,
                    paddingHorizontal: 14,
                    fontSize: 15, color: '#000'
                  }} placeholderTextColor={'#000'} />
                  <Pressable
                  //onPress={() => setProvinceModal(false)}
                  >
                    <View style={{ flexDirection: 'row', justifyContent: 'space-between', alignItems: 'center', marginTop: 10 }}>
                      <TouchableOpacity onPress={() => setCityModal(false)}>
                        <LinearGradient colors={[color.appYellowColor, color.appOrangeColor]} style={{ height: 40, width: 100, borderRadius: 10, marginTop: 0, alignItems: 'center', justifyContent: 'center' }}
                        //style={{flexDirection:'row',justifyContent:'space-between',alignItems:'center'}}
                        >
                          <Text style={{ marginHorizontal: 0, color: '#000', fontSize: 16, marginTop: 0, }}>Cancel</Text>
                        </LinearGradient>
                      </TouchableOpacity>
                      <TouchableOpacity onPress={() => getCityModalApi()}>
                        <LinearGradient colors={[color.appYellowColor, color.appOrangeColor]} style={{ height: 40, width: 100, borderRadius: 10, alignItems: 'center', marginTop: 5, justifyContent: 'center' }}>
                          <Text style={{ marginHorizontal: 0, color: '#000', fontSize: 16, marginTop: 0 }}>Save</Text>
                        </LinearGradient>
                      </TouchableOpacity>
                    </View>
                  </Pressable>
                </View>
              </View>
            </View>
          </Modal>

        </View>

        <Text style={{ marginHorizontal: 20, color: '#000', fontSize: 16 }}>
          Area*
        </Text>
        {areaLoader ? (
          <ActivityIndicator size={'small'} color="#000" />
        ) : (
          <View style={{ flexDirection: 'row', alignItems: 'center', justifyContent: 'flex-start' }}>
            <Dropdown
              style={{
                // height:40,
                // marginHorizontal:20,
                width: '70%',
                paddingLeft: 15,
                height: 50,
                margin: 10,
                backgroundColor: color.appTextBackgoundColor,
                borderRadius: 10,
                marginLeft: 20,
                marginRight: 20,
                fontSize: 15,
                paddingRight: 15,
              }}
              placeholderStyle={{ color: '#000' }}
              itemTextStyle={{ color: '#000' }}
              selectedTextProps={{ style: { color: '#000' } }}
             
              data={areaData}
              search={false}
              maxHeight={300}
              labelField="label"
              valueField="value"
              placeholder="Select Area"
              // searchPlaceholder="5 guests"
              value={areaData.label}
              onChange={item => {
                setArea(item.label);
                areaId = item.value;
                // alert(areaId)
              }}
            />
            <TouchableOpacity onPress={() => setAreaModal(!areaModal)}>
              <Image source={Images.plusIcon} style={{ height: 40, width: 40 }} />
            </TouchableOpacity>
          </View>
        )}

        <View style={styles.centeredView}>
          <Modal
            animationType="slide"
            transparent={true}
            visible={areaModal}
            onRequestClose={() => {
              Alert.alert('Modal has been closed.');
              setAreaModal(!areaModal);
            }}>
            {/* <ScrollView style={{marginVertical:Platform.OS == 'android' ? 100 :200}}> */}
            <View style={styles.centeredView}>
              <View style={styles.modalView}>
                <View style={{ flexDirection: 'row', justifyContent: 'space-between', alignItems: 'center', marginTop: -20 }}>
                  <Text style={{ color: '#000', marginHorizontal: 0, fontSize: 24, fontWeight: '400' }}>Add Area</Text>
                  <Pressable
                    onPress={() => setAreaModal(false)}>
                    <Image source={Images.cancelImageIcon} style={{ height: 40, width: 40, alignSelf: 'flex-end', marginRight: 0 }} />

                  </Pressable>
                </View>
                <Text style={{ marginHorizontal: 0, color: '#000', fontSize: 16 }}>
                  Country*
                </Text>
                {/* {activityIndicator ? (
          <ActivityIndicator size={'small'} color="#000" />
        ) : (  */}

        <ScrollView automaticallyAdjustKeyboardInsets={true} style={{marginBottom:0}}>
          <View style={{}}>
                <View>
                  <Dropdown
                    style={{
                      // height:40,
                      // marginHorizontal:20,
                      paddingLeft: 15,
                      height: 50,
                      // margin: 10,
                      backgroundColor: color.appTextBackgoundColor,
                      borderRadius: 10,
                      // marginLeft: 20,
                      // marginRight: 20,
                      fontSize: 15,
                      paddingRight: 15, marginTop: 10
                    }}
                    // placeholderStyle={styles.placeholderStyle}
                    // selectedTextStyle={styles.selectedTextStyle}
                    // inputSearchStyle={styles.inputSearchStyle}
                    // iconStyle={styles.iconStyle}
                    placeholderStyle={{ color: '#000' }}
                    itemTextStyle={{ color: '#000' }}
                    selectedTextProps={{ style: { color: '#000' } }}
                    data={bookingData}
                    search={true}
                    maxHeight={300}
                    labelField="label"
                    valueField="value"
                    placeholder="Select Country"
                    searchPlaceholder="Search Country"
                    value={bookingData.label}
                    onChange={item => {
                      setcountryName(item.label);
                      countryId = item.value;
                      getProvince();
                      // console.log(item.value)
                    }}
                  />

                </View>
                <View>
                  <View>
                    <Text style={{ marginTop: 5, color: '#000', fontSize: 16 }}>
                      Province*
                    </Text>
                    <Dropdown
                      style={{
                        // height:40,
                        // marginHorizontal:20,
                        width: '100%',
                        paddingLeft: 15,
                        height: 50,
                        // margin: 10,
                        marginTop: 10,
                        backgroundColor: color.appTextBackgoundColor,
                        borderRadius: 10,
                        // marginLeft: 20,
                        // marginRight: 20,
                        fontSize: 15,
                        paddingRight: 15,
                      }}
                      // placeholderStyle={styles.placeholderStyle}
                      // selectedTextStyle={styles.selectedTextStyle}
                      // inputSearchStyle={styles.inputSearchStyle}
                      // iconStyle={styles.iconStyle}
                      placeholderStyle={{ color: '#000' }}
                      itemTextStyle={{ color: '#000' }}
                      selectedTextProps={{ style: { color: '#000' } }}
                      data={provinceData}
                      search={false}
                      maxHeight={300}
                      labelField="label"
                      valueField="value"
                      placeholder="Select Province"
                      // searchPlaceholder="5 guests"
                      value={provinceData.label}
                      onChange={item => {
                        setProvince(item.label);
                        provinceId = item.value;
                        getCity();
                      }}
                    />
                  </View>

                  <View>
                    <Text style={{ marginTop: 5, color: '#000', fontSize: 16 }}>
                      City*
                    </Text>
                    <Dropdown
                      style={{
                        // height:40,
                        // marginHorizontal:20,
                        width: '100%',
                        paddingLeft: 15,
                        height: 50,
                        marginTop: 10,
                        backgroundColor: color.appTextBackgoundColor,
                        borderRadius: 10,

                        fontSize: 15,
                        paddingRight: 15,
                      }}
                      placeholderStyle={{ color: '#000' }}
                      itemTextStyle={{ color: '#000' }}
                      selectedTextProps={{ style: { color: '#000' } }}
                      // placeholderStyle={styles.placeholderStyle}
                      // selectedTextStyle={styles.selectedTextStyle}
                      // inputSearchStyle={styles.inputSearchStyle}
                      // iconStyle={styles.iconStyle}
                      data={cityData}
                      search={false}
                      maxHeight={300}
                      labelField="label"
                      valueField="value"
                      placeholder="Select City"
                      // searchPlaceholder="5 guests"
                      value={areaData.label}
                      onChange={item => {
                        setArea(item.label);
                        areaId = item.value;
                        // alert(areaId)
                      }}
                    />
                  </View>
                  <Text style={{ marginHorizontal: 0, color: '#000', fontSize: 16, marginTop: 5 }}>Area Name*
                  </Text>
                  <TextInput placeholder={'Enter Name*'} value={areaName} onChangeText={(text) => setAreaName(text)} style={{
                    height: 50,
                    width: '100%',
                    // margin: 10,
                    marginTop: 10,
                    backgroundColor: color.appTextBackgoundColor,
                    borderRadius: 10,
                    paddingHorizontal: 14,
                    fontSize: 15, color: '#000'
                  }} placeholderTextColor={'#000'} />

                  <View style={{ flexDirection: 'row', justifyContent: 'space-between', alignItems: 'center', marginTop: 10 }}>
                    <TouchableOpacity onPress={() => setAreaModal(false)}>
                      <LinearGradient colors={[color.appYellowColor, color.appOrangeColor]} style={{ height: 40, width: 100, borderRadius: 10, marginTop: 0, alignItems: 'center', justifyContent: 'center' }}
                      //style={{flexDirection:'row',justifyContent:'space-between',alignItems:'center'}}
                      >
                        <Text style={{ marginHorizontal: 0, color: '#000', fontSize: 16, marginTop: 0, }}>Cancel</Text>
                      </LinearGradient>
                    </TouchableOpacity>
                    <TouchableOpacity onPress={() => getAreaModalApi()}>
                      <LinearGradient colors={[color.appYellowColor, color.appOrangeColor]} style={{ height: 40, width: 100, borderRadius: 10, alignItems: 'center', marginTop: 5, justifyContent: 'center' }}>
                        <Text style={{ marginHorizontal: 0, color: '#000', fontSize: 16, marginTop: 0 }}>Save</Text>
                      </LinearGradient>
                    </TouchableOpacity>
                  </View>
                </View>
                </View>
                </ScrollView>

                <View>

                </View>
              </View>
            </View>
              {/* </ScrollView> */}
          </Modal>

        </View>
        {/* <Text style={{ marginHorizontal: 20, color: '#000', fontSize: 16 }}>Phone Number*</Text>

        <TextView
          placeholder={'Landmark'}
          value={landmark}
          onChangeText={e => setLandmark(e)}
        // isNumeric={false}
        /> */}
        <Text style={{ marginHorizontal: 20, color: '#000', fontSize: 16 }}>
          Address*
        </Text>

        <TextView
          placeholder={'Address'}
          value={address}
          onChangeText={e => setAddress(e)}
        // isNumeric={false}
        />
        <Text style={{ marginHorizontal: 20, color: '#000', fontSize: 16 }}>
          Hosting Type*
        </Text>

        <Dropdown
          style={{
            // height:40,
            // marginHorizontal:20,
            paddingLeft: 15,
            height: 50,
            margin: 10,
            backgroundColor: color.appTextBackgoundColor,
            borderRadius: 10,
            marginLeft: 20,
            marginRight: 20,
            fontSize: 15,
            paddingRight: 15,
          }}
          // placeholderStyle={styles.placeholderStyle}
          // selectedTextStyle={styles.selectedTextStyle}
          // inputSearchStyle={styles.inputSearchStyle}
          // iconStyle={styles.iconStyle}
          placeholderStyle={{ color: '#000' }}
          itemTextStyle={{ color: '#000' }}
          selectedTextProps={{ style: { color: '#000' } }}
          data={hostingType}
          search={false}
          maxHeight={300}
          labelField="label"
          valueField="value"
          placeholder="Hosting Type"
          // searchPlaceholder="5 guests"
          value={hostingType}
          onChange={item => {
            setHosting(item.label);
          }}
        />
        {/* {
                    console.log("hosting type is -----", hosting)

                } */}
        {hosting === 'Business' ? (
          <View>
            <Text style={{ marginHorizontal: 20, color: '#000', fontSize: 16 }}>
              Business Name*
            </Text>

            <TextView
              placeholder={'Business Name'}
              value={businessName}
              onChangeText={e => setBusinessName(e)}
            // isNumeric={false}
            />
            <Text style={{ marginHorizontal: 20, color: '#000', fontSize: 16 }}>
              Upload Business Registration*
            </Text>
            <TouchableOpacity
              onPress={() => setModalVisible1(!modalVisible1)}
              style={{
                backgroundColor: color.appTextBackgoundColor,
                padding: 10,
                marginHorizontal: 20,
                borderRadius: 8,
                flexDirection: 'row',
                alignItems: 'center',
              }}>
              <Text style={{ color: '#000' }}>Choose File</Text>
              <Image
                source={imageUri}
                style={{
                  height: 20,
                  width: 20,
                  marginHorizontal: 20,
                  borderRadius: 4,
                }}
              />
            </TouchableOpacity>
            <ReactNativeModal
              visible={modalVisible1}
              transparent={true}
              style={{ marginBottom: 100 }}
              // onDismiss={() => setModalVisible1(!modalVisible1)}
              onBackdropPress={() => setModalVisible1(!modalVisible1)}>
              <LinearGradient
                colors={[color.appYellowColor, color.appOrangeColor]}
                style={{
                  marginTop: 10,
                  alignSelf: 'center',
                  justifyContent: 'center',
                  alignItems: 'center',
                  flexDirection: 'row',
                  height: 200,
                  width: 200,
                  borderRadius: 20,
                }}>
                <View>
                  <TouchableOpacity
                    onPress={CameraAction}
                    style={{
                      borderWidth: 1,
                      padding: 12,
                      borderRadius: 20,
                      margin: 8,
                      backgroundColor: color.appBlueColor,
                    }}>
                    <Text style={{ fontWeight: '600', color: color.white }}>
                      Camera
                    </Text>
                  </TouchableOpacity>

                  {/* <TouchableOpacity onPress={() => setModalVisible(!modalVisible)} style={{ top: -55 }}>
                            <Image source={Images.cancelImageIcon} style={{ height: 25, width: 25 }} />
                        </TouchableOpacity> */}
                  <TouchableOpacity
                    onPress={GalleryAction}
                    style={{
                      borderWidth: 1,
                      padding: 12,
                      borderRadius: 20,
                      margin: 8,
                      backgroundColor: color.appBlueColor,
                    }}>
                    <Text style={{ fontWeight: '600', color: color.white }}>
                      Gallary
                    </Text>
                  </TouchableOpacity>
                </View>
              </LinearGradient>
            </ReactNativeModal>
          </View>
        ) : (
          <Text></Text>
        )}

        <View style={{ marginLeft: 24, marginTop: 8 }}>
          <Text
            style={{
              fontWeight: '600',
              fontSize: 15,
              color: color.primaryColorBlack,
            }}>
            Password should have at least:
          </Text>
          <View
            style={{
              flexDirection: 'row',
              alignItems: 'center',
              marginTop: 8,
              justifyContent: 'flex-start',
            }}>
            <Image source={Images.BlueDotIcons} style={{ height: 5, width: 5 }} />
            <Text style={{ marginLeft: 6, color: color.appTextColor }}>
              1 capital letter
            </Text>
          </View>
          <View
            style={{ flexDirection: 'row', alignItems: 'center', marginTop: 4 }}>
            <Image source={Images.BlueDotIcons} style={{ height: 5, width: 5 }} />
            <Text style={{ marginLeft: 6, color: color.appTextColor }}>
              1 number
            </Text>
          </View>
          <View
            style={{ flexDirection: 'row', alignItems: 'center', marginTop: 4 }}>
            <Image source={Images.BlueDotIcons} style={{ height: 5, width: 5 }} />
            <Text style={{ marginLeft: 6, color: color.appTextColor }}>
              1 special character(@,$,%,!,&,etc)
            </Text>
          </View>
        </View>
        <View
          style={{
            flexDirection: 'row',
            marginTop: 10,
            marginLeft: 20,
            marginHorizontal: 20,
          }}>
          {/* <CheckBox style={{ width: 20, height: 20 }}
                        disabled={false}
                        value={toggleCheckBox}
                        onValueChange={(newValue) => setToggleCheckBox(newValue)}
                    /> */}

          <CheckBox
            style={{
              width: 20,
              height: 20,
              transform: [{ scaleX:Platform.OS == 'android' ? 1.2 :1 }, { scaleY:Platform.OS == 'android' ? 1.2 : 1 }],
              // borderRadius: 10,
              // borderWidth: 0.5,
              marginTop: 3,
            }}
            boxType='square'
            disabled={false}
            value={toggleCheckBox}
            onValueChange={newValue => setToggleCheckBox(newValue)}
            tintColors={{ true: '#F15927', false: '#C1C1C1' }}
          />
          <Text style={{ marginHorizontal: 20, fontSize: 16, color: '#000' }}>
            I have read and accepted the 
            
            <Text style={{color:color.appBlueColor,fontWeight:"600"}} onPress={() => Linking.openURL(`${MAIN_URL}/privacy-policy`)} > Privacy Policy </Text> 
              and General conditions.
          </Text>
        </View>

        <View style={{ flexDirection: 'row', marginTop: 10, marginLeft: 20 }}>
          {/* <CheckBox style={{ width: 20, height: 20 }}
                        disabled={false}
                        value={toggleCheckBox1}
                        onValueChange={(newValue) => setToggleCheckBox1(newValue)}
                    /> */}
          <CheckBox
            style={{
              width: 20,
              height: 20,
              transform: [{ scaleX:Platform.OS == 'android' ? 1.2 :1 }, { scaleY:Platform.OS == 'android' ? 1.2 : 1 }],
              // borderRadius: 10,
              // borderWidth: 0.5,
              marginTop: 3,
            }}
            boxType='square'
            disabled={false}
            value={toggleCheckBox1}
            onValueChange={newValue => setToggleCheckBox1(newValue)}
            tintColors={{ true: '#F15927', false: '#C1C1C1' }}
          />
          <Text style={{ marginHorizontal: 20, fontSize: 16, width: width / 1.5, color: '#000' }}>
            I agree to recieve commercial information{' '}
          </Text>
        </View>

        <TouchableOpacity
          style={{
            marginTop: 20,
            backgroundColor: color.appOrangeColor,
            width: '90%',
            padding: 10,
            borderRadius: 10,
            justifyContent: 'center',
            marginBottom: 30,
            height: 50,
            marginHorizontal: 20,
          }}
          onPress={
            () => handleValidation()
            // setModalVisible(true)
            //      props.navigation.navigate
            // // ('BecomeHost')
            // ("BecomeaHostHome")
          }>
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
            Register
          </Text>
        </TouchableOpacity>
        {loader ? (
          <View style={{ top: -63, right: -65 }}>
            <ActivityIndicator size={'small'} color="#fff" />
          </View>
        ) : null}
      </ScrollView>
      <View style={commonStyles.centeredView}>
        <Modal
          animationType="slide"
          transparent={true}
          visible={modalVisible}
          onRequestClose={() => {
            Alert.alert('Modal has been closed.');
            setModalVisible(!modalVisible);
          }}>
          <View
            style={{
              flex: 1,

              justifyContent: 'center',
              alignItems: 'center',
              marginTop: 22,
              backgroundColor: 'rgba(0, 0, 0,0.6)',
            }}>
            <View
              style={{
                width: '90%',
                height: 220,
                justifyContent: 'center',
                backgroundColor: '#fff',
                borderRadius: 16,
                marginHorizontal: '10%',
              }}>
              <TouchableOpacity
                style={{ marginRight: '-5%' }}
                onPress={() => setModalVisible(false)}>
                <Image
                  source={Images.cancelImageIcon}
                  style={{
                    // paddingLeft: 70,
                    width: 32,
                    height: 32,
                    borderRadius: 8,
                    borderWidth: 1,
                    marginRight: 30,
                    alignSelf: 'flex-end',
                  }}
                  resizeMode="contain"
                />
              </TouchableOpacity>
              <Text
                style={{
                  color: '#000',
                  fontWeight: '600',
                  fontSize: 16,
                  textAlign: 'center',
                  marginBottom: '3%',
                }}>
                Choose One Action
              </Text>
              <TouchableOpacity
                onPress={() => [
                  props.navigation.navigate(
                    // ('BecomeHost')
                    'BecomeaHostHome',
                    { type: '1' },
                  ),
                  setModalVisible(false),
                ]}
                style={{
                  width: 295,
                  height: 50,
                  borderRadius: 10,
                  alignSelf: 'center',
                  backgroundColor: color.appBlueColor,
                  marginBottom: '6%',
                }}>
                <Text
                  style={{
                    color: color.appWhiteColor,
                    fontWeight: 'normal',
                    fontSize: 16,
                    textAlign: 'center',
                    marginVertical: '3.5%',
                  }}>
                  Home Page
                </Text>
              </TouchableOpacity>

              <TouchableOpacity
                onPress={() => [
                  props.navigation.navigate('AddPropertyguest'),
                  setModalVisible(false),
                ]}>
                <LinearGradient
                  style={{
                    width: 295,
                    height: 50,
                    borderRadius: 10,
                    alignSelf: 'center',
                  }}
                  colors={[color.appYellowColor, color.appOrangeColor]}>
                  <Text
                    style={{
                      color: color.appWhiteColor,
                      fontWeight: 'normal',
                      fontSize: 16,
                      textAlign: 'center',
                      marginVertical: '3.5%',
                    }}>
                    Add Property
                  </Text>
                </LinearGradient>
              </TouchableOpacity>
            </View>
          </View>
        </Modal>
        {/* <Pressable
                    style={[commonStyles.button, commonStyles.buttonOpen]}
                    onPress={() => setModalVisible(true)}
                >
                    <Text style={commonStyles.textStyle}>Show Modal</Text>
                </Pressable> */}
      </View>
      {/* </KeyboardAwareView> */}

    </KeyboardAwareView>
  );
};

const styles = StyleSheet.create({
  input: {
    height: 40,
    margin: 12,
    padding: 20,
    backgroundColor: color.appTextBackgoundColor,
    borderRadius: 4,
  },
  container: {
    marginRight: 10,
    fontSize: 20,
    color: color.appWhiteColor,
    fontWeight: '600',
  },
  AddingBackground: {
    height: 30,
    width: 30,
    borderRadius: 15,
    backgroundColor: color.appTextBackgoundColor,
    alignItems: 'center',
    justifyContent: 'center',
  },

  linearGradient: {
    flex: 1,
    paddingLeft: 15,
    paddingRight: 15,
    width: 300,
  },
  buttonText: {
    fontSize: 18,
    fontFamily: 'Gill Sans',
    textAlign: 'center',
    marginVertical: 20,
    color: '#ffffff',
    marginHorizontal: 50,

    justifyContent: 'center',
    alignSelf: 'center',
    backgroundColor: 'transparent',
  },

  centeredView: {
    flex: 1,
    justifyContent: 'center',
    // alignItems: 'center',
    // marginTop: 22,
  },
  modalView: {
    // margin: 20,
    marginHorizontal: 20,
    backgroundColor: 'white',
    borderRadius: 20,
    padding: 35,
    // alignItems: 'center',
    shadowColor: '#000',
    shadowOffset: {
      width: 0,
      height: 2,
    },
    shadowOpacity: 0.25,
    shadowRadius: 4,
    elevation: 5,
  },
  button: {
    borderRadius: 20,
    padding: 10,
    elevation: 2,
  },
  buttonOpen: {
    backgroundColor: '#F194FF',
  },
  buttonClose: {
    backgroundColor: '#2196F3',
  },
  textStyle: {
    color: 'white',
    fontWeight: 'bold',
    textAlign: 'center',
  },
  modalText: {
    marginBottom: 15,
    textAlign: 'center', color: '#000'
  },
});


export default BecomeaHost;
