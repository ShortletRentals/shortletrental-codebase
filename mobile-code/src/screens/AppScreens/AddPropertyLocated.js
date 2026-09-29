import React, { useState, useContext, useEffect, useRef } from 'react';
import {
  View,
  Image,
  Text,
  ImageBackground,
  StyleSheet,
  TextInput,
  TouchableOpacity,
  _Text,
  ScrollView,
  ActivityIndicator,
  Pressable,
} from 'react-native';
import { color, height, width } from '../../styles/colors';
import Images from '../../styles/Images';
import { commonStyles } from './../../styles/style';
import { Colors, colors } from 'react-native/Libraries/NewAppScreen';
import Header from '../../components/Header';
import TextView from '../../components/TextView';
import LinearGradient from 'react-native-linear-gradient';
import MapView, { Marker } from 'react-native-maps';
import { Toast } from 'react-native-toast-message/lib/src/Toast';
import { Dropdown } from 'react-native-element-dropdown';
import { Context as AuthContext } from '../../context/AuthContext';
import { Context as BookingContext } from '../../context/BookingContext';
import Geocoder from 'react-native-geocoding';
import { useIsFocused, useNavigation, useRoute } from '@react-navigation/native';
import { Modal } from 'react-native';
import { AddArea, AddCity, AddProvince, getAllArea, getAllCity, getAllCountry, getAllProvince } from '../../network/Webconstant';
import axios from 'axios';
import { BackHandler } from 'react-native';
import { KeyboardAwareView } from 'react-native-keyboard-aware-view';

let countryId = '';
let provinceId = '';
let cityId = '';
let areaId = '';
let selectedName = '';

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
const streetNumbering = [
  { label: 'Kilometer', value: '1' },
  { label: 'Number', value: '2' },
  { label: 'Other', value: '3' },
  { label: 'Without Number', value: '4' },
];

const streetTypeData = [
  { label: 'Alley', value: '1' },
  { label: 'Avenue', value: '2' },
  { label: 'Boulevard', value: '3' },
  { label: 'Circle', value: '4' },
  { label: 'Cul-de-sac', value: '5' },
  { label: 'Passage', value: '6' },
  { label: 'Path', value: '7' },
  { label: 'Road', value: '8' },
  { label: 'Roundabout', value: '9' },
  { label: 'Square', value: '10' },
  { label: 'Street', value: '11' },
  { label: 'Walk', value: '12' },
  { label: 'Way', value: '13' },
];
const floor = [
  { label: '0', value: '1' },
  { label: '1', value: '2' },
  { label: '2', value: '3' },
  { label: '3', value: '4' },
  { label: '4', value: '4' },
  { label: '5', value: '5' },
  { label: '6', value: '6' },
  { label: '7', value: '7' },
  { label: '8', value: '8' },
  { label: '9', value: '9' },
  { label: '10', value: '10' },
  { label: '11', value: '11' },
  { label: '12', value: '12' },
  { label: '13', value: '13' },
  { label: '14', value: '14' },
  { label: '15', value: '15' },
  { label: '16', value: '16' },
  { label: '17', value: '17' },
  { label: '18', value: '18' },
  { label: '19', value: '19' },
  { label: '20', value: '20' },
];
const stairCase = [
  { label: 'Yes', value: '1' },
  { label: 'No', value: '2' },
];
const Elevator = [
  { label: 'Yes', value: '1' },
  { label: 'No', value: '2' },
];

const AddPropertyLocated = props => {
  const route = useRoute();

  // const [mobileNumber, setMobileNumber] = useState('')
  const [address1, setAddress1] = useState('');
  const [address2, setAddress2] = useState('');
  // const [city, setCity] = useState('')
  const [country, setCountry] = useState('');
  // const [area, setArea] = useState('')
  const [postalCode, setLsetPostalCodeandmark] = useState('');
  const [streetName, setStreetName] = useState('');
  const [doorNumber, setDoorNumber] = useState('');
  const [countryCodeNew, setcountryCode] = useState(null);
  const [countryNameNew, setcountryName] = useState(null);
  const [province, setProvince] = useState(null);
  const [city, setCity] = useState(null);
  const [area, setArea] = useState(null);
  const [myLat, setMyLat] = useState(37.78825);
  const [myLong, setMyLong] = useState(-122.4324);
  //uncommmennt this line
  const [userId, setUserId] = useState(route?.params?.userId);
  const [propertyId, setPropertyId] = useState(route?.params?.propertyId);
  const [categoryId, setCategoryId] = useState(route?.params?.categoryId);
  const [selectedFloor, setSelectedFloor] = useState(null);
  const [streetNumber, setStreetNumber] = useState(null);
  const [elevator, setElevator] = useState(null);
  const [stairCaseSelected, setStairCaseSelected] = useState(null);
  const [streetType, setStreetType] = useState(null);
  const [loader, setLoader] = useState(false);
  const [cityLoader, setCityLoader] = useState(false);
  const [areaLoader, setAreaLoader] = useState(false);

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
  let _mapView = useRef(null);

  useEffect(() => {

    _mapView.current.animateToRegion({
      latitude: myLat,
      longitude: myLong,
      latitudeDelta: 0.04,
      longitudeDelta: 0.05,
    });
    getAllCountryApi();

  }, [props]);

  const getAllCountryApi = async () => {
    try {
      axios({
        method: 'get',
        url: getAllCountry,

      })
        .then(function (response) {
          if (response.data.status === true) {

            let arr = []
            response.data.data.map(item => {
              arr.push({ value: item.id, label: item.name });
            });
            setBookingData(arr)
          }
        })
        .catch(error => console.log('something went wrong ', error));

    } catch (error) {
      console.log('eerrr', error);
    }
  };

  const getLocation = () => {
    // Geocoder.init('AIzaSyAplPUk9niQdlpNdKgipVXUnrd6Nev5TX4'); // use a valid API key
    Geocoder.init('AIzaSyArqlT_3Q9fHcisw6lvvUGTcObXGz3GEJk'); // use a valid API key

    Geocoder.from(selectedName)
      .then(json => {
        var location = json.results[0].geometry.location;
        // console.log('---loc',location);
        // _mapView.current.fitToElements(true);
        setMyLat(location.lat);
        setMyLong(location.lng);
        _mapView.current.animateToRegion({
          latitude: location.lat,
          longitude: location.lng,
          latitudeDelta: 0.04,
          longitudeDelta: 0.05,
        });

      })
      .catch(error => console.warn(error));
  };

  const handler = () => {
    props.navigation.goBack()
  }
  useEffect(() => {
    const backAction = () => {
      handler()
      return true;
    };

    const backHandler = BackHandler.addEventListener(
      'hardwareBackPress',
      backAction,
    );

    return () => backHandler.remove();
  }, [props])
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
          // let temp = []
          // response.data.data.map(item => {
          //   temp.push({ value: item.id, label: item.name });
          // });
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
          // let temp =[]
          //  response.data.data.map(item => {
          //   temp.push({ value: item.id, label: item.name });
          // });
          // setCityData(temp)
          Toast.show({
            type: 'success',
            text1: 'Shortlet',
            text2: response.data.message
          })
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




  const handleValidation = () => {
    if (address1 == '') {
      Toast.show({
        type: 'error',
        text1: 'Shortlet',
        text2: 'Please enter address',
      });
    }
    else if (countryNameNew === null) {
      Toast.show({
        type: 'error',
        text1: 'Shortlet',
        text2: 'Please select country ',
      });
    } else if (province == null) {
      Toast.show({
        type: 'error',
        text1: 'Shortlet',
        text2: 'Please select province ',
      });
    }
    // else if (province == null) {
    //   Toast.show({
    //     type: 'error',
    //     text1: 'Shortlet',
    //     text2: 'Please select province',
    //   });
    // }
    else if (city == null) {
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
    } else {
      props.navigation.navigate('AddPropertyWelcome', {
        propertyId: propertyId,
        userId: userId,
        categoryId: categoryId,
        address: address1,
        latitude: myLat,
        longitude: myLong,
        countryId: countryId,
        provinceId: provinceId,
        cityId: cityId,
        areaId: areaId,
        postalCode: postalCode,
        streetName: streetName,
        streetType: streetNumber,
        selectFloor: selectedFloor,
        stairs: stairCaseSelected,
        elevator: elevator,
        apertNo: doorNumber,
      });
    }
  };

  return (
    <KeyboardAwareView style={{ flex: 1, backgroundColor: '#fff' }}>
      <Header
        props={props}
        Heading={'Add Property'}
        onPress={() => props.navigation.goBack()}
      />
      <View style={{ flex: 1 }}>
        <KeyboardAwareView>
          <ScrollView>
            <Text
              style={{
                marginTop: 20,
                color: color.primaryColorBlack,
                fontSize: 17,
                fontWeight: '500',
                marginHorizontal: 20,
              }}>
              Where's your place located?
            </Text>

            {/* <TextView
                        placeholder={'Address (line2)'}
                        value={address2}
                        onChangeText={(e) => setAddress2(e)}
                    /> */}
            <TextView
              placeholder={'Building/house no.'}
              value={address1}
              onChangeText={e => {
                setAddress1(e.replace(/[^0-9]/g, ''))
                selectedName = e
                getLocation()
              }

              }
              isNumeric={true}
            />
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
                paddingRight: 15,
              }}

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
                selectedName = item.label;
                getProvince();
                getLocation();
                // console.log(item.value)
              }}
            />

            {loader ? (
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
                    selectedName = item.label;
                    getCity();
                    getLocation();
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
                      //onPress={() => setProvinceModal(false)}
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
                    selectedName = item.label;

                    getArea();
                    getLocation();
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
                    <ScrollView automaticallyAdjustKeyboardInsets={true}>
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
                    </ScrollView>
                  </View>
                </View>
              </Modal>

            </View>
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
                  // placeholderStyle={styles.placeholderStyle}
                  // selectedTextStyle={styles.selectedTextStyle}
                  // inputSearchStyle={styles.inputSearchStyle}
                  // iconStyle={styles.iconStyle}
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
                    selectedName = item.label;
                    getLocation();

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
                    <ScrollView automaticallyAdjustKeyboardInsets={true}>
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
                              console.log('l', propertyId);
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
                            placeholder="Select Area"
                            // searchPlaceholder="5 guests"
                            value={cityData.label}
                            onChange={item => {
                              setArea(item.label);
                              areaId = item.value;
                              // alert(areaId)
                            }}
                          />
                        </View>
                        <Text style={{ marginHorizontal: 0, color: '#000', fontSize: 16, marginTop: 5 }}>City Name*
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
                    </ScrollView>
                    <View>

                    </View>
                  </View>
                </View>
              </Modal>

            </View>
            <TextView
              placeholder={'Postal Code'}
              value={postalCode}
              onChangeText={e => {
                setLsetPostalCodeandmark(e),
                  selectedName = e
                getLocation()
              }
              }
              isNumeric={true}
              maxLength={6}
            />
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
              placeholderStyle={{ color: '#000' }}
              itemTextStyle={{ color: '#000' }}
              selectedTextProps={{ style: { color: '#000' } }}
              // placeholderStyle={styles.placeholderStyle}
              // selectedTextStyle={styles.selectedTextStyle}
              // inputSearchStyle={styles.inputSearchStyle}
              // iconStyle={styles.iconStyle}
              data={streetTypeData}
              search={false}
              maxHeight={300}
              labelField="label"
              valueField="value"
              placeholder="Street Type"
              // searchPlaceholder="5 guests"
              value={streetTypeData}
              onChange={item => {
                setStreetType(item.label);
              }}
            />
            <TextView
              placeholder={'Street Name'}
              value={streetName}
              onChangeText={e => {
                setStreetName(e),
                  selectedName = e
                getLocation()
              }
              }
            />
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
              placeholderStyle={{ color: '#000' }}
              itemTextStyle={{ color: '#000' }}
              selectedTextProps={{ style: { color: '#000' } }}
              // placeholderStyle={styles.placeholderStyle}
              // selectedTextStyle={styles.selectedTextStyle}
              // inputSearchStyle={styles.inputSearchStyle}
              // iconStyle={styles.iconStyle}
              data={streetNumbering}
              search={false}
              maxHeight={300}
              labelField="label"
              valueField="value"
              placeholder="Type of Street Numbering"
              // searchPlaceholder="5 guests"
              value={streetNumbering}
              onChange={item => {
                setStreetNumber(item.label);
              }}
            />

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
              placeholderStyle={{ color: '#000' }}
              itemTextStyle={{ color: '#000' }}
              selectedTextProps={{ style: { color: '#000' } }}
              // placeholderStyle={styles.placeholderStyle}
              // selectedTextStyle={styles.selectedTextStyle}
              // inputSearchStyle={styles.inputSearchStyle}
              // iconStyle={styles.iconStyle}
              data={floor}
              search={false}
              maxHeight={300}
              labelField="label"
              valueField="value"
              placeholder="Select Floor"
              // searchPlaceholder="5 guests"
              value={floor}
              onChange={item => {
                setSelectedFloor(item.label);
              }}
            />
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
              placeholderStyle={{ color: '#000' }}
              itemTextStyle={{ color: '#000' }}
              selectedTextProps={{ style: { color: '#000' } }}
              // placeholderStyle={styles.placeholderStyle}
              // selectedTextStyle={styles.selectedTextStyle}
              // inputSearchStyle={styles.inputSearchStyle}
              // iconStyle={styles.iconStyle}
              data={stairCase}
              search={false}
              maxHeight={300}
              labelField="label"
              valueField="value"
              placeholder="Select Staircase"
              // searchPlaceholder="5 guests"
              value={stairCase}
              onChange={item => {
                setStairCaseSelected(item.label);
              }}
            />
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
              placeholderStyle={{ color: '#000' }}
              itemTextStyle={{ color: '#000' }}
              selectedTextProps={{ style: { color: '#000' } }}
              // placeholderStyle={styles.placeholderStyle}
              // selectedTextStyle={styles.selectedTextStyle}
              // inputSearchStyle={styles.inputSearchStyle}
              // iconStyle={styles.iconStyle}
              data={Elevator}
              search={false}
              maxHeight={300}
              labelField="label"
              valueField="value"
              placeholder="Select Elevator"
              // searchPlaceholder="5 guests"
              value={Elevator}
              onChange={item => {
                setElevator(item.label);
              }}
            />
            <TextView
              placeholder={'Apartment door no.'}
              value={doorNumber}
              onChangeText={e => {
                setDoorNumber(e.replace(/[^0-9]/g, '')),
                selectedName = e
                getLocation()
              }
              }
              isNumeric={true}
            />
            <Text
              style={{
                marginTop: 10,
                fontSize: 17,
                fontWeight: '500',
                marginHorizontal: 20,
                color: color.primaryColorBlack,
              }}>
              Show your specific location
            </Text>

            <MapView
              apikey={'AIzaSyArqlT_3Q9fHcisw6lvvUGTcObXGz3GEJk'}
              ref={_mapView}
              initialRegion={{
                latitude: myLat,
                longitude: myLong,
                latitudeDelta: 0.04,
                longitudeDelta: 0.05,
              }}
              // zoomControlEnabled
              mapType='standard'
              
              showsBuildings


              style={{
                flex: 1,
                marginTop: 5,
                marginHorizontal: 20,
                height: width * (217 / 375),
                borderWidth: 1,
                borderRadius: 20,
              }}>
              <Marker coordinate={{ latitude: myLat, longitude: myLong }} />
            </MapView>


          </ScrollView>
        </KeyboardAwareView>
        <View style={{}}>
          <View
            style={{
              padding: 20,
              backgroundColor: color.appTextBackgoundColor,
              flexDirection: 'row',
              justifyContent: 'space-between',
              marginTop: 20,
            }}>
            <TouchableOpacity
              onPress={() => props.navigation.goBack()}
              style={{ backgroundColor: color.appBlueColor, borderRadius: 10 }}>
              <Text
                style={{
                  fontSize: 18,
                  color: color.appWhiteColor,
                  marginHorizontal: 40,
                  marginVertical: 20,
                  fontWeight: '600',
                }}>
                Back
              </Text>
            </TouchableOpacity>
            <View>
              <TouchableOpacity onPress={() => handleValidation()}>
                <LinearGradient
                  colors={[color.appYellowColor, color.appOrangeColor]}
                  style={(styles.linearGradient, { borderRadius: 10 })}>
                  <Text
                    style={{
                      marginHorizontal: 40,
                      marginVertical: 20,
                      color: color.appWhiteColor,
                      fontWeight: '600',
                      fontSize: 18,
                    }}>
                    Next
                  </Text>
                </LinearGradient>
              </TouchableOpacity>
            </View>
          </View>
        </View>
      </View>
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
  linearGradient: {
    flex: 1,
    paddingLeft: 15,
    paddingRight: 15,
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

export default AddPropertyLocated;
