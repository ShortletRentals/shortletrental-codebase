import React, {useState, useContext, useEffect} from 'react';
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
} from 'react-native';
import {color, height, width} from '../../styles/colors';
import Images from '../../styles/Images';
import {commonStyles} from './../../styles/style';
import {Colors, colors} from 'react-native/Libraries/NewAppScreen';
import Header from '../../components/Header';
import TextView from '../../components/TextView';
import LinearGradient from 'react-native-linear-gradient';
import MapView, {Marker} from 'react-native-maps';
import {Toast} from 'react-native-toast-message/lib/src/Toast';
import {Dropdown} from 'react-native-element-dropdown';
import {Context as AuthContext} from '../../context/AuthContext';
import {Context as BookingContext} from '../../context/BookingContext';
import Geocoder from 'react-native-geocoding';
import {useIsFocused, useNavigation, useRoute} from '@react-navigation/native';

let countryId = '';
let provinceId = '';
let cityId = '';
let areaId = '';
let selectedName = '';

const data = [
  {label: 'Company', value: '1'},
  {label: 'Dr', value: '2'},
  {label: 'Family', value: '3'},
  {label: 'Mr & Mrs', value: '4'},
  {label: 'Mr.', value: '5'},
  {label: 'Mrs.', value: '5'},
  {label: 'Ms.', value: '5'},
  {label: 'PhD', value: '5'},
  {label: 'Prof', value: '5'},
];
const streetNumbering = [
  {label: 'Kilometer', value: '1'},
  {label: 'Number', value: '2'},
  {label: 'Other', value: '3'},
  {label: 'Without Number', value: '4'},
];
const floor = [
  {label: '0', value: '1'},
  {label: '1', value: '2'},
  {label: '2', value: '3'},
  {label: '3', value: '4'},
  {label: '4', value: '4'},
  {label: '5', value: '5'},
  {label: '6', value: '6'},
  {label: '7', value: '7'},
  {label: '8', value: '8'},
  {label: '9', value: '9'},
  {label: '10', value: '10'},
  {label: '11', value: '11'},
  {label: '12', value: '12'},
  {label: '13', value: '13'},
  {label: '14', value: '14'},
  {label: '15', value: '15'},
  {label: '16', value: '16'},
  {label: '17', value: '17'},
  {label: '18', value: '18'},
  {label: '19', value: '19'},
  {label: '20', value: '20'},
];
const stairCase = [
  {label: 'Yes', value: '1'},
  {label: 'No', value: '2'},
];
const Elevator = [
  {label: 'Yes', value: '1'},
  {label: 'No', value: '2'},
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
  const [userId, setUserId] = useState(route.params.userId);
  const [propertyId, setPropertyId] = useState(route.params.propertyId);
  const [categoryId, setCategoryId] = useState(route.params.categoryId);
  const [selectedFloor, setSelectedFloor] = useState(null);
  const [streetNumber, setStreetNumber] = useState(null);
  const [elevator, setElevator] = useState(null);
  const [stairCaseSelected, setStairCaseSelected] = useState(null);

  const {
    signUpUser,
    sendOtpHandler,
    state: {activityIndicator, Mobile, country_code},
  } = useContext(AuthContext);
  const {
    getAllCountryApi,
    getAreaApi,
    updateBookingData,
    getProvinceApi,
    getCityApi,
    getAllCountryCodeApi,
    state: {bookingData, provinceData, cityData, areaData, countryData},
  } = useContext(BookingContext);

  useEffect(() => {
    console.log(
      'add property located',
      route.params.userId,
      route.params.propertyId,
      route.params.categoryId,
    );

    getAllCountryApi();
    getAllCountryCodeApi();
  }, []);

  const getLocation = () => {

      Geocoder.init("AIzaSyAplPUk9niQdlpNdKgipVXUnrd6Nev5TX4"); // use a valid API key
      // With more options
      // Geocoder.init("xxxxxxxxxxxxxxxxxxxxxxxxx", {language : "en"}); // set the language

      // Search by address
      Geocoder.from(selectedName)
          .then(json => {
              var location = json.results[0].geometry.location;
              setMyLat(location.lat)
              setMyLong(location.lng)

              console.log("location is====", location);

          })
          .catch(error => console.warn(error));
  }

  const getProvince = () => {
    let data = {country_id: countryId, province_id: provinceId};
    getProvinceApi(data);
    // console.log("===",countryId)
    //  axios({
    //     method: "post",
    //     url: "https://zea-virtual-events.com/shortletrental/api/auth/getAllProvince",
    //    data:{country_id:countryId}
    //   })
    // .then((response) => {
    //   if (response?.data?.status == true) {
    //     // console.log("get country=======iiiiiiiiii",response.data.data)
    //     countryName=response.data.data.map((item) => { return { value: item.id, label: item.name } })
    //     console.log("==========Name",countryName)

    //   } else {
    //     console.log("api error", response);
    //   }
    // })
    // .catch((e) => {
    // });
  };

  const getCity = () => {
    let data = {country_id: countryId, province_id: provinceId};
    // console.log("=====data",data)
    getCityApi(data);
  };

  // console.log("all city is ====",cityData)

  const getArea = () => {
    let data = {
      country_id: countryId,
      province_id: provinceId,
      city_id: cityId,
    };
    console.log('=====data', data);
    getAreaApi(data);
  };

  const handleValidation = () => {
    if (address1 == '') {
      Toast.show({
        type: 'error',
        text1: 'Shortlet',
        text2: 'Please enter address',
      });
    } else if (countryNameNew === null) {
      Toast.show({
        type: 'error',
        text1: 'Shortlet',
        text2: 'Please enter country name',
      });
    } else if (province == null) {
      Toast.show({
        type: 'error',
        text1: 'Shortlet',
        text2: 'Please select province name',
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
    <View style={{flex: 1, backgroundColor: '#fff'}}>
      <Header
        props={props}
        Heading={'Add Property'}
        onPress={() => props.navigation.goBack()}
      />
      <View style={{flex: 1}}>
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
          <TextView
            placeholder={'Building/house no.'}
            value={address1}
            onChangeText={e => setAddress1(e)}
          />
          {/* <TextView
                        placeholder={'Address (line2)'}
                        value={address2}
                        onChangeText={(e) => setAddress2(e)}
                    /> */}

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
            data={bookingData.country}
            search={false}
            maxHeight={300}
            labelField="label"
            valueField="value"
            placeholder="Select Country"
            // searchPlaceholder="5 guests"
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
            data={provinceData.country}
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
            data={cityData.country}
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
            data={areaData.country}
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
          <TextView
            placeholder={'Postal Code'}
            value={postalCode}
            onChangeText={e => setLsetPostalCodeandmark(e)}
            isNumeric={true}
            maxLength={6}
          />
          <TextView
            placeholder={'Street Name'}
            value={streetName}
            onChangeText={e => setStreetName(e)}
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
            onChangeText={e => setDoorNumber(e)}
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
            initialRegion={{
              latitude: myLat,
              longitude: myLong,
              latitudeDelta: myLat,
              longitudeDelta: myLong,
            }}
            style={{
              flex: 1,
              marginTop: 5,
              marginHorizontal: 20,
              height: width * (117 / 375),
              borderWidth: 1,
              borderRadius: 20,
            }}>
            <Marker coordinate={{latitude: myLat, longitude: myLong}} />
          </MapView>

          {/* <View style={{ flex: 1, justifyContent: 'flex-end', marginBottom: 30 }}>
                    <View style={{ backgroundColor: color.appTextBackgoundColor, flexDirection: 'row', justifyContent: 'space-between', marginHorizontal: 15, marginTop: 20 }}>
                        <TouchableOpacity onPress={()=>props.navigation.navigate('AddProperty')} style={{ backgroundColor: color.appBlueColor, borderRadius: 10 }}><Text style={{ fontSize: 18, color: color.appWhiteColor, marginHorizontal: 40, marginVertical: 20, fontWeight: '600' }}>Back</Text></TouchableOpacity >
                        <View>
                            <TouchableOpacity onPress={()=>props.navigation.navigate('AddPropertyWelcome')}>
                            <LinearGradient colors={[color.appYellowColor, color.appOrangeColor]} style={styles.linearGradient, { borderRadius: 10 }}>
                                <Text style={{ marginHorizontal: 40, marginVertical: 20, color: color.appWhiteColor, fontWeight: '600', fontSize: 18 }} >
                                    Next
                                </Text>
                            </LinearGradient>
                            </TouchableOpacity>
                        </View>
                    </View>
                </View> */}
        </ScrollView>
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
              style={{backgroundColor: color.appBlueColor, borderRadius: 10}}>
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
                  style={(styles.linearGradient, {borderRadius: 10})}>
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
    </View>
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
});

export default AddPropertyLocated;








22 feb
1. HOME SCREEN header design issue 
2. HOME SCREEN bottom design issue
3. HOTEL DETAILS modal close icon design
4. HOTEL DETAILS host image size issue
5. BECOMEHOST get started change
6. BECOME a HOST radio button issue
7. BOOKING check checkbox
8. CheckBoxFilter check box change
9. MYBOOKING Re-Book text style change

23 feb 
1. AddPropertyLocated Crashed issue
2. CheckBox.js check box change
3. ChatMessage header design issue