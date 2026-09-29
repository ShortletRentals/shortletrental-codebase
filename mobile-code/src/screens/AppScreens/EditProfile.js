import React, { useState, useEffect, useRef } from 'react';
import {
  View,
  Image,
  Text,
  ImageBackground,
  TouchableOpacity,
  _Text,
  ScrollView,
  TouchableWithoutFeedback,
  ActivityIndicator,
  RefreshControl,
  Platform,
} from 'react-native';
import { color, height, width } from '../../styles/colors';
import Images from '../../styles/Images';
import { commonStyles } from './../../styles/style';
import { Colors, colors } from 'react-native/Libraries/NewAppScreen';
import Header from '../../components/Header';
import TextView from '../../components/TextView';
import CheckBox from '@react-native-community/checkbox';
import { launchCamera, launchImageLibrary } from 'react-native-image-picker';
import { Calendar } from 'react-native-calendars';

import axios from 'axios';
import FlashMessage, { showMessage } from 'react-native-flash-message';
import AsyncStorage from '@react-native-async-storage/async-storage';
import { useFocusEffect, useIsFocused, useNavigation, useRoute } from '@react-navigation/native';
import { useCallback } from 'react';
import { Update_Profile, User_Profile } from '../../network/Webconstant';
import { useContext } from 'react';
import { Context as ProfileContext } from '../../context/ProfileContext';
import { Context as AuthContext } from '../../context/AuthContext';
import { Context as BookingContext } from '../../context/BookingContext';
import Modal, { ReactNativeModal } from 'react-native-modal';
import moment from 'moment';
import { Toast } from 'react-native-toast-message/lib/src/Toast';
import LinearGradient from 'react-native-linear-gradient';
import { Dropdown } from 'react-native-element-dropdown';
import { TextInput } from 'react-native';
// import DatePicker from "react-native-date-picker";
import { SliderShimmer } from '../../components/Skeleton';
import RNDateTimePicker from '@react-native-community/datetimepicker';
import { BackHandler } from 'react-native';
import { KeyboardAwareView } from 'react-native-keyboard-aware-view';
import { GooglePlacesAutocomplete } from 'react-native-google-places-autocomplete';
import { placeholderTextColor } from 'deprecated-react-native-prop-types/DeprecatedTextInputPropTypes';

var radio_props = [
  { label: 'Male', value: 'Male' },
  { label: 'Female', value: 'Female' },
];
var radio_props1 = [
  { label: 'Married', value: 'Married' },
  { label: 'UnMarried', value: 'UnMarried' },
];

// const data1 = [
//     {
//         label: 'Male'
//     },
// ]
// const data2 = [{
//     label: 'Female'
// }
// ];
let provinceId = '';
let countryId = '';
let cityId = '';

const EditProfile = props => {
  const ref = useRef()
  const navigation = useNavigation()
  const { profileData } = props?.route.params;

  const [name, setName] = useState(profileData.name);
  const [date1, setDate1] = useState(
    new Date(moment(ProfileUserData?.dob).format('YYYY-MM-DD')),
  );
  const [date, setDate] = useState(new Date());
  const [toggleCheckBox, setToggleCheckBox] = useState(false);
  const [toggleCheckBox1, setToggleCheckBox1] = useState(false);
  const [married, setMarried] = useState(false);
  const [unMarried, setUnMarried] = useState(true);
  const [modalVisible, setModalVisible] = useState(false);
  const [imageUri, setImageUri] = useState('');
  // const [gender,setGender] = useState(data)
  const [value, setValue] = useState(
    ProfileUserData?.gender == null ? 'Male' : ProfileUserData?.gender,
  );
  const [value1, setValue1] = useState(
    ProfileUserData?.marital_status == null
      ? 'Married'
      : ProfileUserData?.marital_status,
  );
  const [value3, setValue3] = useState('');
  const [modalOpen, setModalOpen] = useState(false);

  const [loader, setLoader] = useState(false);
  const [isLoading, setIsLoading] = useState(true);
  const [ProfileUserData, setProfileUserData] = useState({});
  const route = useRoute();
  const [province, setProvince] = useState(null);
  const [city, setCity] = useState(null);
  const [street, setStreet] = useState('');
  const [streetNumber, setStreetNumber] = useState('');
  const [postalCode, SetPostalCode] = useState('');
  const [cityLoader, setCityLoader] = useState(false);
  const [countryLoader, setCountryLoader] = useState(false);
  const [lat, setLat] = useState('')
  const [long, setLong] = useState('')

  const {
    getAllCountryApi,
    updateBookingData,
    getProvinceApi,
    getCityApi,
    getAreaApi,
    state: { bookingData, provinceData, cityData },
  } = useContext(BookingContext);
  const [refreshing, setRefreshing] = React.useState(false);

  const onRefresh = React.useCallback(() => {
    setRefreshing(true);
    getUserProfile();
    setTimeout(() => {
      setRefreshing(false);
    }, 2000);
  }, []);

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

  const CameraAction = () => {
    let options = {
      maxWidth: 512,
      maxHeight: 512,
      quality: 0.3,
      allowsEditing: false,
      noData: true,
      storageOption: {
        path: 'images',
        mediaType: 'photo',
      },
      includeBase64: true,
    };
    launchCamera(options, response => {
      // console.log('Response =', response);
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
        setModalVisible(false);
        setImageUri(response?.assets[0]);
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
      // console.log('Response =', response);
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
        console.log('else');
        setModalVisible(false);
        setImageUri(response?.assets[0]);
      }
    });
  };

  // console.log('value is',modalVisible, ProfileUserData.dob, ProfileUserData.marital_status, ProfileUserData.gender, toggleCheckBox, toggleCheckBox1);

  useEffect(() => {
    setImageUri('');

    getUserProfile();
    getAllCountryApi();
    // getLocation()
  }, [props]);


  const getLocation = (lat, lng) => {
    fetch(
      'https://maps.googleapis.com/maps/api/geocode/json?address=' +
      lat +
      ',' +
      lng +
      '&key=AIzaSyArqlT_3Q9fHcisw6lvvUGTcObXGz3GEJk',
    )
      .then(response => response.json())
      .then(responseJson => {
        console.log("selected address is =====", responseJson.results[0].formatted_address);
        // var myLocation = responseJson.results[0].address_components;
        // console.log('=====', myLocation[0].long_name);
        // setLocation(responseJson.results[0].address_components);
        // setLandmark(responseJson.results[0].address_components[2]?.long_name)
        // setCity(responseJson.results[0].address_components[4]?.long_name)

        setStreet(responseJson.results[0].formatted_address);
        // setModalVisible(!modalVisible);

        // console.log('ADDRESS GEOCODE is BACK!! => ' + JSON.stringify(responseJson.results[0].address_components[5].long_name));
        // stateName = responseJson.results[0].address_components.filter(x => x.types.filter(t => t == 'administrative_area_level_1').length > 0)[0].short_name;
        // areaName = responseJson.results[0].address_components.filter(x => x.types.filter(t => t == 'sublocality_level_1').length > 0)[0].short_name;
        // console.log('statename', stateName, areaName, responseJson.results[0].address_components);
        // setAddress(responseJson.results[0].address_components)
      });
    // console.log(position?.coords, 'position');
    // setUserLat(position?.coords?.latitude);
    // setUserLong(position?.coords?.longitude);
    // setMapInitRegion({
    //   latitude: position?.coords?.latitude,
    //   longitude: position?.coords?.longitude,
    //   latitudeDelta: 0.0922,
    //   longitudeDelta: 0.0421,
    // });
    // let region = {
    //   latitude: position?.coords?.latitude,
    //   longitude: position?.coords?.longitude,
    //   latitudeDelta: 0.0922,
    //   longitudeDelta: 0.0421,
    // }
    // _map?.current?.animateToRegion(region, 500);

    // console.log('=====', userLat, '===lng', userLong);
    // get_list();
    // getPlaceFromLatLong(
    //   position?.coords?.latitude,
    //   position?.coords?.longitude,
    // );
    //   },
    //   error => { },
    //   { enableHighAccuracy: true, timeout: 15000, maximumAge: 10000 },
    // );
  };

  const getUserProfile = async () => {
    setIsLoading(true);
    const Localtoken = await AsyncStorage.getItem('token_id');
    // myToken = myToken ? Localtoken : null
    try {
      await axios({
        method: 'get',
        url: User_Profile,
        headers: {
          "Accept": 'application/json',
          "Authorization": `Bearer ${Localtoken}`,
        },
      }).then(function (res) {
        console.log(
          '-----------------------------------res',
          res.data.data.postal_code
        );
        if (res.data.status) {
          setIsLoading(false);
          setProfileUserData(res.data.data);
          // setStreetNumber(res.data.data.street_number);
          setStreet(res.data.data.street)
          if (res.data.data.postal_code !== null) {
            console.log('kljkljlkjkj')
            SetPostalCode(res.data.data.postal_code);
          } else {
            SetPostalCode('')
          }
          if (res.data.data.dob == null) {
            setDate(new Date());
          } else {
            setDate(res.data.data.dob);
          }
          if (res.data.data.gender == null) {
            setToggleCheckBox('')
          } else {

            setToggleCheckBox(res.data.data?.gender)
          }
          if (res.data.data.marital_status == null) {
            setToggleCheckBox1('')
          } else {

            setToggleCheckBox1(res.data.data.marital_status)
          }

        } else {
          setIsLoading(false);
        }
      });
    } catch (error) {
      console.log('something went wrong', error);
      setIsLoading(false);
    }
  };

  console.log('=-----------------------------image uri', street);

  const getProvince = () => {
    // console.log('---------province-----------');
    setCountryLoader(true);
    let data = { country_id: countryId, province_id: provinceId };
    getProvinceApi(data);
    setTimeout(() => {
      setCountryLoader(false);
    }, 2000);
  };

  const getCity = (id) => {
    console.log('------------get city-----------', id);
    setCityLoader(true);

    let data = { country_id: countryId, province_id: id };
    // console.log("=====data",data)
    getCityApi(data);
    setTimeout(() => {
      setCityLoader(false);
    }, 2000);
  };

  const getArea = () => {
    // setAreaLoader(true);
    // console.log('---------get area ---------');
    let data = {
      country_id: countryId,
      province_id: provinceId,
      city_id: cityId,
    };
    console.log('=====data', data);
    getAreaApi(data);
    // setTimeout(() => {
    //   setAreaLoader(false);
    // }, 2000);
  };

  const onProfileEditHandler = async () => {
    // console.log('---------------profile edit handler-----------');

    const data = new FormData();
    data.append('full_name', name);
    data.append('email', route.params.ProfileEmail);
    data.append('gender', toggleCheckBox);
    // if (imageUri != '') {
    {
      imageUri != ''
        ? data.append('image', {
          uri: imageUri?.uri,
          type: imageUri?.type,
          name: imageUri?.fileName,
        })
        : data.append('image', ProfileUserData?.image);
    }
    // }

    data.append('marital_status', toggleCheckBox1);
    data.append('dob', moment(date).format('YYYY-MM-DD'));
    data.append('country_id', countryId);
    data.append('province_id', provinceId);
    data.append('city_id', cityId);
    data.append('street', street);
    data.append('street_number', streetNumber);
    data.append('postal_code', postalCode);
    // console.log('data is',data);
    // let data = {
    //     full_name: name,
    //     email: route.params.ProfileEmail,
    //     dob: date,
    //     gender: value,
    //     marital_status: value1,
    //     image:{ uri: imageUri?.uri,
    //         type: imageUri?.type,
    //         name: imageUri?.fileName}
    //     // imageUri?.uri
    // }
    const Localtoken = await AsyncStorage.getItem('token_id');
    setLoader(true);
    try {
      await axios({
        method: 'post',
        url: Update_Profile,
        data: data,
        headers: {
          Authorization: `Bearer ${Localtoken}`,
          Accept: 'multipart/form-data',
          'Content-Type': 'multipart/form-data',
        },
      }).then(function (response) {
        if (response.data.status == true) {
          // console.log('====update profiel res', response)
          Toast.show({
            type: 'success',
            text1: 'Shortlet',
            text2: response.data.message,
          });
          setLoader(false);
          setImageUri('');
          props?.navigation?.navigate('Account');
        } else {
          Toast.show({
            type: 'error',
            text1: 'Shortlet',
            text2: response.data.message,
          });
          setLoader(false);
        }
      });
    } catch (error) {
      console.log('error', error, data);
      Toast.show({
        type: 'error',
        text1: 'Shortlet',
        text2: 'Network Error',
      });
      setLoader(false);
    }
  };




  const onChange = (event, selectedDate) => {
    // console.log('-------------------selected', selectedDate, event);
    // setDate(selectedDate)
    setDate(selectedDate);
    ProfileUserData.dob = selectedDate
    setModalOpen(false);
    // on cancel set date value to previous date
    // if (event?.type === 'dismissed') {
    //     setDate(date);
    //     return;
    // }
  };

  return (
    <View style={[commonStyles.container, { color: color.appWhiteColor }]}>
      <Header
        props={props}
        Heading={'Edit Profile'}
        onPress={() => props.navigation.navigate('Account')}
      />
      {isLoading ? (
        <SliderShimmer />
      ) : (
        <KeyboardAwareView>
          <ScrollView
          showsVerticalScrollIndicator={false}
            keyboardShouldPersistTaps='always'
            contentContainerStyle={{ flexGrow: 1 }}
            refreshControl={
              <RefreshControl refreshing={refreshing} onRefresh={onRefresh} />
            }>
            <TouchableWithoutFeedback
              onPress={() => setModalVisible(false)}>
              <ReactNativeModal
                visible={modalVisible}
                transparent={true}
                style={{ marginBottom: 100 }}
                // onDismiss={() => setModalVisible(!modalVisible)}
                onBackdropPress={() => setModalVisible(!modalVisible)}
              >

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

                  <View style={{ width: 200, padding: 10 }}>
                    <TouchableOpacity onPress={() => setModalVisible(false)} style={{ padding: 0, marginBottom: 10, alignSelf: 'flex-end' }}>
                      <Image source={Images.cancelImageIcon} style={{}} />
                    </TouchableOpacity>
                    <TouchableOpacity
                      onPress={CameraAction}
                      style={{
                        borderWidth: 1,
                        padding: 12,
                        borderRadius: 20,
                        margin: 8,
                        backgroundColor: color.appBlueColor,
                      }}>
                      <Text style={{ fontWeight: '600', color: color.white, textAlign: 'center' }}>
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
                      <Text style={{ fontWeight: '600', color: color.white, textAlign: 'center' }}>
                        Gallary
                      </Text>
                    </TouchableOpacity>
                  </View>

                </LinearGradient>
              </ReactNativeModal>
            </TouchableWithoutFeedback>
            <View
              style={{
                width: 167,
                height: 167,
                marginBottom: 10,
                alignSelf: 'center',
                marginTop: 20,
                borderRadius: 80,
                borderWidth: 1,
                backgroundColor: '#fff',
                borderColor: '#ffffff80',
              }}>
              <Image
                source={
                  // {imageUri}
                  imageUri ? imageUri : { uri: ProfileUserData?.image }
                }
                // {Images.ProfileImageIcons}
                style={{
                  width: 167,
                  height: 167,
                  resizeMode: 'stretch',
                  alignSelf: 'center',
                  borderRadius: 80,
                  borderWidth: 1,
                }}
              />
              <TouchableOpacity
                onPress={() => setModalVisible(true)}
                style={{
                  alignSelf: 'center',
                  left: width * (55 / 375),
                  top: width * (-44 / 375),
                }}>
                <Image
                  source={Images.UploadIcons}
                  style={{ height: 32, width: 32 }}
                />
              </TouchableOpacity>
            </View>
            <Text
              style={{
                fontSize: 15,
                fontWeight: '400',
                color: color.primaryColorBlack,
                marginHorizontal: 20,
                marginTop: 20
              }}>
              Full Name
            </Text>
            <TextView
              placeholder={
                ProfileUserData.fullname == null
                  ? 'Full Name'
                  : ProfileUserData.fullname
              }
              // placeholder={USERDATA.fullname == null ? 'Full Name' : USERDATA.fullname}
              value={name}
              onChangeText={e => setName(e)}
            />
            <Text
              style={{
                fontSize: 15,
                fontWeight: '400',
                color: color.primaryColorBlack,
                marginHorizontal: 20,
                marginTop: 0
              }}>
              Email
            </Text>
            <View
              style={{
                paddingLeft: 15,
                height: 50,
                margin: 10,
                backgroundColor: color.appTextBackgoundColor,
                borderRadius: 10,
                marginLeft: 20,
                marginRight: 20,
                fontSize: 15,
                justifyContent: 'center',
                // alignItems:'center'
              }}>
              <Text style={{ color: '#000' }}>{ProfileUserData?.email}</Text>
            </View>
            <Text
              style={{
                fontSize: 15,
                fontWeight: '400',
                color: color.primaryColorBlack,
                marginHorizontal: 20,
                marginTop: 0
              }}>
              Date Of Birth
            </Text>
            <TouchableOpacity
              onPress={() => setModalOpen(!modalOpen)}
              style={{
                paddingLeft: 15,
                height: 50,
                margin: 10,
                backgroundColor: color.appTextBackgoundColor,
                borderRadius: 10,
                marginLeft: 20,
                marginRight: 20,
                fontSize: 15,
                justifyContent: 'center',
                // alignItems:'center'
              }}>

              {ProfileUserData?.dob == null ? (
                <Text style={{ color: '#000' }}>
                  Select Date Of Birth
                  {/* {moment(date).format('YYYY-MM-DD')} */}
                </Text>
              ) : (
                <Text style={{ color: '#000' }}>
                  {moment(date).format('YYYY-MM-DD')}
                </Text>
              )}
              {/* {
                        modalOpen ? <Text>Date of Birth</Text> :  <Text>{moment(date1).format('YYYY-MM-DD')}</Text>
                    } */}
              {/* <Text> {route?.params?.ProfileDob == null ? route?.params?.ProfileDob : moment(date1).format('YYYY-MM-DD')}</Text> */}
            </TouchableOpacity>
            {/* <TouchableWithoutFeedback onPress={() => setModalOpen(!modalOpen)}> */}

            {modalOpen ? (
              <RNDateTimePicker
                display="spinner"
                value={new Date(date)}
                onChange={onChange}
              //    onChange={(e)=>setDate1(moment(e.nativeEvent.timestamp).format('YYYY-MM-DD'))}
              />
            ) : null}



            <View style={{ marginLeft: 17 }}>
              <Text
                style={{
                  fontSize: 15,
                  fontWeight: '400',
                  color: color.primaryColorBlack,
                  marginLeft: 8,
                }}>
                Gender
              </Text>
              <View style={{ flexDirection: 'row' }}>

                <View
                  style={{
                    flexDirection: 'row',
                    marginTop: 10,
                    height: 40,
                    marginLeft: 8,
                  }}>
                  <TouchableOpacity
                    onPress={() => {
                      setToggleCheckBox("Male")
                    }

                    }>
                    <Image
                      source={
                        toggleCheckBox == "Male" ? Images.selectedIcons : Images.whiteDot
                      }
                      style={{ height: 20, width: 20 }}
                    />
                  </TouchableOpacity>
                  <Text
                    style={{
                      paddingLeft: 10,
                      paddingRight: 10,
                      fontSize: 15,
                      color: '#000',
                    }}>
                    Male
                  </Text>
                </View>
                <View style={{ flexDirection: 'row', marginTop: 10 }}>
                  <TouchableOpacity
                    onPress={() => [
                      setToggleCheckBox("Female")
                    ]}>
                    <Image
                      source={
                        toggleCheckBox == "Female" ? Images.selectedIcons : Images.whiteDot

                      }
                      style={{ height: 20, width: 20 }}
                    />
                  </TouchableOpacity>
                  <Text
                    style={{
                      paddingLeft: 10,
                      paddingRight: 10,
                      fontSize: 15,
                      color: '#000',
                    }}>
                    Female
                  </Text>
                </View>
              </View>

              <Text
                style={{
                  fontSize: 15,
                  fontWeight: '400',
                  color: color.primaryColorBlack,
                  marginLeft: 8,
                }}>
                Marital Status
              </Text>
              <View style={{ flexDirection: 'row' }}>
                <View
                  style={{
                    flexDirection: 'row',
                    marginTop: 10,
                    height: 40,
                    width: 120,
                    marginHorizontal: 50,
                    justifyContent: 'center',
                  }}>
                  <View
                    style={{
                      flexDirection: 'row',
                      marginTop: 10,
                      height: 40,
                      marginLeft: 8,
                    }}>
                    <TouchableOpacity
                      onPress={() =>
                        setToggleCheckBox1("Married")
                      }>
                      <Image
                        source={
                          toggleCheckBox1 == "Married" ? Images.selectedIcons : Images.whiteDot
                        }
                        style={{ height: 20, width: 20 }}
                      />
                    </TouchableOpacity>
                    <Text
                      style={{
                        paddingLeft: 10,
                        paddingRight: 10,
                        fontSize: 15,
                        color: '#000',
                      }}>
                      Married
                    </Text>
                  </View>
                  <View style={{ flexDirection: 'row', marginTop: 10 }}>
                    <TouchableOpacity
                      onPress={() =>
                        setToggleCheckBox1("Unmarried")
                      }>
                      <Image
                        source={toggleCheckBox1 == "Unmarried" ? Images.selectedIcons : Images.whiteDot}
                        style={{ height: 20, width: 20 }}
                      />
                    </TouchableOpacity>
                    <Text
                      style={{
                        paddingLeft: 10,
                        paddingRight: 10,
                        fontSize: 15,
                        color: '#000',
                      }}>
                      Un-married
                    </Text>
                  </View>
                </View>
              </View>
            </View>
            <View style={{ borderWidth: 0 }}>
              {/* <Text style={{ marginHorizontal: 20, color: '#000', fontSize: 16, }}>ADDRESS</Text> */}
              <View>
                <Text style={{ marginHorizontal: 20, color: '#000', fontSize: 16 }}>
                  Country
                </Text>
                <Dropdown
                  style={{
                    paddingLeft: 15,
                    height: 50,
                    margin: 10,
                    backgroundColor: color.appTextBackgoundColor,
                    borderRadius: 10,
                    marginLeft: 20,
                    marginRight: 20,
                    fontSize: 15,
                    paddingRight: 12,
                  }}
                  itemTextStyle={{ color: '#000' }}
                  data={bookingData.country || []}
                  search={true}
                  maxHeight={300}
                  labelField="label"
                  valueField="value"
                  selectedTextProps={{ style: { color: '#000' } }}
                  placeholder={
                    ProfileUserData?.country_name == ''
                      ? 'Select Country'
                      : ProfileUserData?.country_name
                  }
                  placeholderStyle={{ color: '#000' }}
                  value={bookingData.label}
                  onChange={item => {
                    setValue3(item.label);
                    countryId = item.value;
                    getProvince();
                  }}
                />
                <Text style={{ marginHorizontal: 20, color: '#000', fontSize: 16 }}>
                  Province
                </Text>
                {countryLoader ? (
                  <ActivityIndicator size={'small'} color="#000" />
                ) : (
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
                    selectedTextProps={{ style: { color: '#000' } }}
                    itemTextStyle={{ color: '#000' }}
                    placeholderStyle={{ color: '#000' }}
                    data={provinceData.country || []}
                    search={false}
                    maxHeight={300}
                    labelField="label"
                    valueField="value"
                    placeholder={
                      ProfileUserData?.province_name == ''
                        ? 'Select Province'
                        : ProfileUserData?.province_name
                    }
                    // searchPlaceholder="5 guests"
                    value={provinceData.label}
                    onChange={item => {
                      setProvince(item.label);
                      provinceId = item.value;
                      getCity(item.value);
                    }}
                  />
                )}
                <Text style={{ marginHorizontal: 20, color: '#000', fontSize: 16 }}>
                  City
                </Text>
                {cityLoader ? (
                  <ActivityIndicator size={'small'} color="#000" />
                ) : (
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
                    selectedTextProps={{ style: { color: '#000' } }}
                    itemTextStyle={{ color: '#000' }}
                    placeholderStyle={{ color: '#000' }}
                    selectedTextStyle={{ color: '#000' }}
                    data={cityData.country || []}
                    search={false}
                    maxHeight={300}
                    labelField="label"
                    valueField="value"
                    placeholder={
                      ProfileUserData?.city_name == ''
                        ? 'Select City'
                        : ProfileUserData?.city_name
                    }
                    // searchPlaceholder="5 guests"
                    value={cityData.label}
                    onChange={item => {
                      setCity(item.label);
                      cityId = item.value;
                      // getArea();
                    }}
                  />
                )}
                <Text style={{ marginHorizontal: 20, color: '#000', fontSize: 16 }}>
                  Address
                </Text>
                <GooglePlacesAutocomplete
                  GooglePlacesDetailsQuery={{ fields: 'geometry' }}
                  fetchDetails={true}
                  ref={ref}
                  onPress={(data, details = null) => {
                    console.log(
                      'location ======== ', data, details,
                      JSON.stringify(data),
                      'details---------',
                      JSON.stringify(details.geometry.location),
                    );
                    let myLat = details.geometry.location.lat;
                    let myLong = details.geometry.location.lng;
                    setLat(details.geometry.location.lat)
                    setLong(details.geometry.location.lng)

                    getLocation(myLat, myLong);

                  }}

                  query={{
                    key: 'AIzaSyArqlT_3Q9fHcisw6lvvUGTcObXGz3GEJk',
                    language: 'en',
                  }}

                  placeholder='Address'
                  minLength={2}
                  autoFocus={true}
                  returnKeyType={'default'}
                  // value={street}
                  
                  textInputProps={{
                    value: street,
                    onChangeText: (e) => setStreet(e),

                    // defaultValue:street
                  }}

                  // fetchDetails={true}
                  // renderLeftButton={() => {
                  //   return (
                  //     <TouchableOpacity
                  //       onPress={() => setModalVisible(!modalVisible)}
                  //       style={{justifyContent: 'center'}}>
                  //       <Image
                  //         source={Images.ArrowIcons}
                  //         style={{height: 20, width: 30,
                  //           // tintColor:currentTheme().backIconColor
                  //         }}
                  //       />
                  //     </TouchableOpacity>
                  //   );
                  // }}
                  styles={{
                    textInputContainer: {
                      marginTop: 10
                      // backgroundColor: 'grey',
                      // backgroundColor: color.appTextBackgoundColor,
                    },
                    textInput: {
                      height: 50,
                      width: '100%',
                      color: '#5d5d5d',
                      fontSize: 16,
                      marginHorizontal: 20,
                      backgroundColor: color.appTextBackgoundColor,
                    },
                    predefinedPlacesDescription: {
                      color: '#1faadb',
                    },
                  }}
                />
                {/* <Text style={{color:'#000'}}>{street}</Text> */}
                {/* <TextInput
                style={{
                  paddingLeft: 15,
                  height: 50,
                  margin:10 ,
                  backgroundColor: color.appTextBackgoundColor,
                  borderRadius: 10,
                  marginLeft: 20,
                  marginRight: 20,
                  fontSize: 15,
                  color: '#000',
                }}
                // multiline
                placeholder="Address"
                placeholderTextColor={'#000'}
                value={
                  ProfileUserData?.street == undefined || null ? street : street
                }
                onChangeText={text => setStreet(text)}
              /> */}
                {/* <Text style={{ marginHorizontal: 20, color: '#000', fontSize: 16 }}>
                Street Number
              </Text>
              <TextInput
                style={{
                  paddingLeft: 15,
                  height: 50,
                  margin: 10,
                  backgroundColor: color.appTextBackgoundColor,
                  borderRadius: 10,
                  marginLeft: 20,
                  marginRight: 20,
                  fontSize: 15,
                  color: '#000',
                }}
                keyboardType="numeric"
                // multiline
                placeholder="Street Number"
                placeholderTextColor={'#000'}
                defaultValue={
                  streetNumber == undefined || "null"
                    ? ""
                    : streetNumber
                }
                onChangeText={text =>
                  setStreetNumber(text.replace(/[^0-9]/g, ''))
                }
              /> */}
                <Text style={{ marginHorizontal: 20, color: '#000', fontSize: 16 }}>
                  Postal Code
                </Text>
                <TextInput
                  style={{
                    paddingLeft: 15,
                    height: 50,
                    margin: 10,
                    backgroundColor: color.appTextBackgoundColor,
                    borderRadius: 10,
                    marginLeft: 20,
                    marginRight: 20,
                    fontSize: 15,
                    color: '#000',
                  }}
                  placeholder="Postal Code"
                  placeholderTextColor={'#000'}
                  // multiline
                  defaultValue={
                    ProfileUserData?.postal_code == "undefined" || ProfileUserData?.postal_code == "null"
                      ? ""
                      : postalCode
                  }
                  onChangeText={text =>
                    SetPostalCode(text.replace(/[^0-9]/g, ''))
                  }
                  onBlur={() => { postalCode.length < 5 && alert('please enter postal code minimum length 6') }}
                  keyboardType="numeric"
                  maxLength={6}


                />
              </View>
            </View>
            <TouchableOpacity
              onPress={() => onProfileEditHandler()}
              // onPress={() => props.navigation.navigate('Account')}

              style={{
                marginTop: 20,
                backgroundColor: color.appOrangeColor,
                width: '90%',
                padding: 10,
                borderRadius: 10,
                justifyContent: 'center',
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
                Save
              </Text>
            </TouchableOpacity>
            {loader ? (
              <View style={{ top: -35, right: -50 }}>
                <ActivityIndicator size={'small'} color="#fff" />
              </View>
            ) : null}
            <View style={{ marginBottom: 20 }} />
          </ScrollView>
        </KeyboardAwareView>
      )
      }
    </View >
  );
};

export default EditProfile;
