import React, { useState, useContext, useEffect } from "react";
import { View, Image, Text, ImageBackground, StyleSheet, TextInput, TouchableOpacity, _Text, ScrollView } from "react-native";
import { color, height, width } from "../../styles/colors";
import Images from "../../styles/Images";
import { commonStyles } from "./../../styles/style";
import { Colors, colors } from "react-native/Libraries/NewAppScreen";
import Header from "../../components/Header";
import TextView from "../../components/TextView";
import LinearGradient from "react-native-linear-gradient";
import MapView, { Marker } from 'react-native-maps';
import { Toast } from "react-native-toast-message/lib/src/Toast";
import { Dropdown } from "react-native-element-dropdown";
import { Context as AuthContext } from "../../context/AuthContext";
import { Context as BookingContext } from '../../context/BookingContext'
import Geocoder from 'react-native-geocoding';
import { useIsFocused, useNavigation, useRoute } from "@react-navigation/native";
import { BackHandler } from "react-native";
import { KeyboardAwareView } from "react-native-keyboard-aware-view";









let countryId = "";
let provinceId = "";
let cityId = "";
let areaId = "";
let selectedName = ""




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
const maxGuest = [
    { label: '1', value: '1' },
    { label: '2', value: '2' },
    { label: '3', value: '3' },
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
const floor = [
    
    { label: '1', value: '1' },
    { label: '2', value: '2' },
    { label: '3', value: '3' },
    { label: '4', value: '4' },
    { label: '5', value: '5' },
    { label: '6', value: '6' },
    { label: '7', value: '7' },
    { label: '8', value: '8' },
    { label: '9', value: '9' },
    { label: '10', value: '10' },





];
const stairCase = [
    { label: 'Yes', value: '1' },
    { label: 'No', value: '2' },


];
const numberOfNights = [
    { label: '1', value: '1' },
    { label: '2', value: '2' },
    { label: '3', value: '3' },



];
const Elevator = [
    { label: 'Yes', value: '1' },
    { label: 'No', value: '2' },


];
const Estate = [
    { label: '--Select--', value: '0' },
    { label: 'Yes', value: '1' },
    { label: 'No', value: '2' },
];
const EstateRoad = [
    { label: '--Select--', value: '0' },
    { label: 'Yes', value: '1' },
    { label: 'No', value: '2' },
];
const dayBooking = [
    { label: '--Select--', value: '0' },
    { label: 'Owner', value: '1' },
    { label: 'Facility Manager', value: '2' },
    { label: 'Agent', value: '3' },


];
const HomeSupport = [
    { label: '--Select--', value: '0' },
    { label: 'None', value: '1' },
    { label: 'Hosting Parties', value: '2' },
    { label: 'Hosting get together/mini-events', value: '3' },


];



const AddPropertyLocated1 = (props) => {
    const route = useRoute()

    const [value, setValue] = useState(null)
    const [address1, setAddress1] = useState('')
    const [wifi, setWifi] = useState('')
    const [wifiPassword, setWifiPassword] = useState('')
    const [teleLocation, setTeleLocation] = useState('')
    const [camera, setCamera] = useState('')

    const [houseRules, setHouseRules] = useState('')
    const [responseTime, setResponseTime] = useState('')
    const [responsibilities, setResponsibilities] = useState('')





    const [address2, setAddress2] = useState('')
    // const [city, setCity] = useState('')
    const [country, setCountry] = useState('')
    // const [area, setArea] = useState('')
    const [postalCode, setLsetPostalCodeandmark] = useState('')
    const [streetName, setStreetName] = useState('')
    const [doorNumber, setDoorNumber] = useState('')
    const [countryCodeNew, setcountryCode] = useState(null);
    const [countryNameNew, setcountryName] = useState(null);
    const [numberNights, setNumberOfNights] = useState(null);
    const [allowDayBooking, setAllowDayBooking] = useState(null);
    const [estate, setEstate] = useState(null);
    const [estateName, setEstateName] = useState(null);
    const [estateLandmark, setEstateLandmark] = useState(null);
    const [estateRoad, setEstateRoad] = useState(null);
    const [homeSupport, setHomeSupport] = useState(null)
    const [parties, setParties] = useState(null)

    const [petsAllow, setPetsAllow] = useState(null);
    const [cctv, setCctv] = useState(null);
    const [television, setTelevision] = useState(null);




    const [province, setProvince] = useState(null);
    const [city, setCity] = useState(null);
    const [area, setArea] = useState(null);
    const [myLat, setMyLat] = useState(37.78825);
    const [myLong, setMyLong] = useState(-122.4324);







    const {
        signUpUser, sendOtpHandler,
        state: { activityIndicator, Mobile, country_code }
    } = useContext(AuthContext);
    const { getAllCountryApi, getAreaApi, updateBookingData, getProvinceApi, getCityApi, getAllCountryCodeApi, state: { bookingData, provinceData, cityData, areaData, countryData } } = useContext(BookingContext)



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

    useEffect(() => {   
     getAllCountryApi()
        getAllCountryCodeApi()
    }, [])





    const handleValidation = () => {
        if (countryNameNew == null) {
            Toast.show({
                type: 'error',
                text1: "Shortlet",
                text2: "Please select guest",


            })
        }

        else if (numberNights === null) {
            Toast.show({
                type: 'error',
                text1: "Shortlet",
                text2: "Please select minimum number of nights",


            })
        }
        else {
            props.navigation.navigate('AddPropertyPhotos', {
                propertyId: route.params.propertyId, userId: route.params.userId,
                categoryId: route.params.categoryId,
                address: route.params.address, latitude: route.params.latitude, longitude: route.params.longitude,
                countryId: route.params.countryId,
                provinceId: route.params.propertyId, cityId: route.params.cityId, areaId: route.params.areaId,
                postalCode: route.params.postalCode, streetName: route.params.streetName,
                streetType: route.params.streetType, selectFloor: route.params.selectFloor, stairs: route.params.stairs,
                elevator: route.params.elevator, apertNo: route.params.apertNo, beds: route.params.beds, bedrooms: route.params.bedrooms,
                bathrooms: route.params.bathrooms, kitchen: route.params.kitchen,
                //  amenityId: route.params.amenityId,
                maxGuest: countryNameNew, petsAllow: petsAllow, numberOfNights: numberNights, cctv: cctv, cameraLocation: camera,
                wifiUserName: wifi, wifiPassword: wifiPassword, television: television, televisionLocation: teleLocation,
                allowDayBooking: allowDayBooking,
                houseRules: houseRules, responseTime: responseTime
            })
        }

    }



    return (
        <View style={{ flex: 1, backgroundColor: '#fff' }}>

            <Header props={props} Heading={'Add Property'} onPress={() => props.navigation.goBack()} />
            <View style={{ flex: 1 }}>
                <KeyboardAwareView>
                <ScrollView>
                    <Text style={{ marginTop: 20,marginBottom:10, color: color.primaryColorBlack, fontSize: 17, fontWeight: '500', marginHorizontal: 20 }}>
                        {/* Where's your place located? */}
                        More information about the accommodation
                        </Text>
                    {/* <TextView
                        placeholder={'Building/house no.'}
                        value={address1}
                        onChangeText={(e) => setAddress1(e)}
                    /> */}
                    {/* <TextView
                        placeholder={'Address (line2)'}
                        value={address2}
                        onChangeText={(e) => setAddress2(e)}
                    /> */}
                    <Text style={{ marginHorizontal: 20, color: '#000' }}>Select Max Guest*</Text>
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
                            paddingRight: 15
                        }}
                        placeholderStyle={{color:'#000'}}
          itemTextStyle={{color:'#000'}}
          selectedTextProps={{style:{color:'#000'}}}
                        // placeholderStyle={styles.placeholderStyle}
                        // selectedTextStyle={styles.selectedTextStyle}
                        // inputSearchStyle={styles.inputSearchStyle}
                        // iconStyle={styles.iconStyle}
                        data={maxGuest}
                        search={false}
                        maxHeight={300}
                        labelField="label"
                        valueField="value"
                        placeholder="Select Max Guest"
                        // searchPlaceholder="5 guests"
                        value={maxGuest}
                        onChange={item => {
                            setcountryName(item.label);
                          
                        }} />
                    <Text style={{ marginHorizontal: 20, color: '#000' }}>Pets Allow</Text>
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
                            paddingRight: 15
                        }}
                        placeholderStyle={{color:'#000'}}
          itemTextStyle={{color:'#000'}}
          selectedTextProps={{style:{color:'#000'}}}
                        // placeholderStyle={styles.placeholderStyle}
                        // selectedTextStyle={styles.selectedTextStyle}
                        // inputSearchStyle={styles.inputSearchStyle}
                        // iconStyle={styles.iconStyle}
                        data={stairCase}
                        search={false}
                        maxHeight={300}
                        labelField="label"
                        valueField="value"
                        placeholder="Pets Allow"
                        // searchPlaceholder="5 guests"
                        value={stairCase}
                        onChange={item => {
                            setPetsAllow(item.label);
                        }} />
                    <Text style={{ marginHorizontal: 20, color: '#000' }}>Minimum number of nights</Text>
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
                            paddingRight: 15
                        }}
                        placeholderStyle={{color:'#000'}}
          itemTextStyle={{color:'#000'}}
          selectedTextProps={{style:{color:'#000'}}}
                        // placeholderStyle={styles.placeholderStyle}
                        // selectedTextStyle={styles.selectedTextStyle}
                        // inputSearchStyle={styles.inputSearchStyle}
                        // iconStyle={styles.iconStyle}
                        data={numberOfNights}
                        search={false}
                        maxHeight={300}
                        labelField="label"
                        valueField="value"
                        placeholder="Minimum number of nights"
                        // searchPlaceholder="5 guests"
                        value={numberOfNights}
                        onChange={item => {
                            setNumberOfNights(item.label);
                        }} />
                    <Text style={{ marginHorizontal: 20, color: '#000' }}>Select CCTV*</Text>
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
                            paddingRight: 15
                        }}placeholderStyle={{color:'#000'}}
                        itemTextStyle={{color:'#000'}}
                        selectedTextProps={{style:{color:'#000'}}}
                        // placeholderStyle={styles.placeholderStyle}
                        // selectedTextStyle={styles.selectedTextStyle}
                        // inputSearchStyle={styles.inputSearchStyle}
                        // iconStyle={styles.iconStyle}
                        data={Elevator}
                        search={false}
                        maxHeight={300}
                        labelField="label"
                        valueField="value"
                        placeholder="Select CCTV"
                        // searchPlaceholder="5 guests"
                        value={Elevator}
                        onChange={item => {
                            setCctv(item.label);
                        }} />
                    {
                        cctv == 'Yes' ?
                            <View>
                                <Text style={{ marginHorizontal: 20, color: '#000' }}>Indiate location of cameras</Text>
                                <TextView
                                    placeholder={'Indiate location of cameras'}
                                    value={camera}
                                    onChangeText={(e) => setCamera(e)}
                                />
                            </View>
                            : null}
                    <Text style={{ marginHorizontal: 20, color: '#000' }}>Wi-Fi Username</Text>
                    <TextView
                        placeholder={'Wi-Fi Username'}
                        value={wifi}
                        onChangeText={(e) => setWifi(e)}
                    />
                    <Text style={{ marginHorizontal: 20, color: '#000' }}>Wi-Fi-Password</Text>
                    <TextView
                        placeholder={'Wi-Fi Password'}
                        value={wifiPassword}
                        onChangeText={(e) => setWifiPassword(e)}
                    />
                    <Text style={{ marginHorizontal: 20, color: '#000' }}>Select Televisions</Text>
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
                            paddingRight: 15
                        }}placeholderStyle={{color:'#000'}}
                        itemTextStyle={{color:'#000'}}
                        selectedTextProps={{style:{color:'#000'}}}
                        // placeholderStyle={styles.placeholderStyle}
                        // selectedTextStyle={styles.selectedTextStyle}
                        // inputSearchStyle={styles.inputSearchStyle}
                        // iconStyle={styles.iconStyle}
                        data={floor}
                        search={false}
                        maxHeight={300}
                        labelField="label"
                        valueField="value"
                        placeholder="Select Televisions"
                        // searchPlaceholder="5 guests"
                        value={floor}
                        onChange={item => {
                            setTelevision(item.label);
                        }} />
                    <Text style={{ marginHorizontal: 20, color: '#000' }}>Location of Television</Text>
                    <TextView
                        placeholder={'Enter Location of Television'}
                        value={teleLocation}
                        onChangeText={(e) => setTeleLocation(e)}
                    />
                    {/* <Text style={{ marginHorizontal: 20, color: '#000' }}>House rules</Text>
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
                            paddingRight: 15
                        }}
                        // placeholderStyle={styles.placeholderStyle}
                        // selectedTextStyle={styles.selectedTextStyle}
                        // inputSearchStyle={styles.inputSearchStyle}
                        // iconStyle={styles.iconStyle}
                        data={dayBooking}
                        search={false}
                        maxHeight={300}
                        labelField="label"
                        valueField="value"
                        placeholder="Allow day Booking"
                        // searchPlaceholder="5 guests"
                        value={dayBooking}
                        onChange={item => {
                            setAllowDayBooking(item.label);
                        }} /> */}
                    <Text style={{ marginHorizontal: 20, color: '#000' }}>House rules</Text>
                    <TextView
                        placeholder={'House Rules'}
                        value={houseRules}
                        onChangeText={(e) => setHouseRules(e)}
                    />
                    <Text style={{ marginHorizontal: 20, color: '#000' }}>What is your response time when a guest has a complaint with your property?</Text>
                    <TextView
                        placeholder={'Response Time'}
                        value={responseTime}
                        onChangeText={(e) => setResponseTime(e)}
                    />
                    <Text style={{ marginHorizontal: 20, color: '#000' }}>What is your responsibility for this apartment you intend to list on the platform?</Text>
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
                            paddingRight: 15
                        }}
                        placeholderStyle={{color:'#000'}}
          itemTextStyle={{color:'#000'}}
          selectedTextProps={{style:{color:'#000'}}}
                        // placeholderStyle={styles.placeholderStyle}
                        // selectedTextStyle={styles.selectedTextStyle}
                        // inputSearchStyle={styles.inputSearchStyle}
                        // iconStyle={styles.iconStyle}
                        data={dayBooking}
                        search={false}
                        maxHeight={300}
                        labelField="label"
                        valueField="value"
                        // placeholder="Allow day Booking"
                        // searchPlaceholder="5 guests"
                        value={dayBooking}
                        onChange={item => {
                            setAllowDayBooking(item.label);
                        }} />
                    {
                        allowDayBooking == 'Agent' ? <View>
                            <Text style={{ marginHorizontal: 20, color: '#000', }}>If these titles do not best explain your roles, Kindly explain your responsibilities</Text>
                            <TextView placeholder={'Explain your responsibilities'} value={responsibilities} onChangeText={(text) => setResponsibilities(text)} />
                        </View> : null
                    }
                    <Text style={{ marginHorizontal: 20, color: '#000', }}>Is your property located within an estate?</Text>
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
                            paddingRight: 15
                        }}
                        placeholderStyle={{color:'#000'}}
          itemTextStyle={{color:'#000'}}
          selectedTextProps={{style:{color:'#000'}}}
                        // placeholderStyle={styles.placeholderStyle}
                        // selectedTextStyle={styles.selectedTextStyle}
                        // inputSearchStyle={styles.inputSearchStyle}
                        // iconStyle={styles.iconStyle}
                        data={Estate}
                        search={false}
                        maxHeight={300}
                        labelField="label"
                        valueField="value"
                        // placeholder="Allow day Booking"
                        // searchPlaceholder="5 guests"
                        value={Estate}
                        onChange={item => {
                            setEstate(item.label);
                        }} />
                    {
                        estate == 'Yes' ? <View>
                            <Text style={{ marginHorizontal: 20, color: '#000', }}>Estate Name</Text>
                            <TextView placeholder={'Estate Name'} value={estateName} onChangeText={(text) => setEstateName(text)} />
                            <Text style={{ marginHorizontal: 20, color: '#000', }}>Landmark</Text>
                            <TextView placeholder={'Landmark'} value={estateLandmark} onChangeText={(text) => setEstateLandmark(text)} />
                        </View> : null
                    }
                    <Text style={{ marginHorizontal: 20, color: '#000', }}>Is the street where your property located on tarred road?</Text>
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
                            paddingRight: 15
                        }}placeholderStyle={{color:'#000'}}
                        itemTextStyle={{color:'#000'}}
                        selectedTextProps={{style:{color:'#000'}}}
                        // placeholderStyle={styles.placeholderStyle}
                        // selectedTextStyle={styles.selectedTextStyle}
                        // inputSearchStyle={styles.inputSearchStyle}
                        // iconStyle={styles.iconStyle}
                        data={EstateRoad}
                        search={false}
                        maxHeight={300}
                        labelField="label"
                        valueField="value"
                        // placeholder="Allow day Booking"
                        // searchPlaceholder="5 guests"
                        value={EstateRoad}
                        onChange={item => {
                            setEstateRoad(item.label);
                        }} />
                    <Text style={{ marginHorizontal: 20, color: '#000', }}>Which of the following does your home support?</Text>
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
                            paddingRight: 15
                        }}placeholderStyle={{color:'#000'}}
                        itemTextStyle={{color:'#000'}}
                        selectedTextProps={{style:{color:'#000'}}}
                        // placeholderStyle={styles.placeholderStyle}
                        // selectedTextStyle={styles.selectedTextStyle}
                        // inputSearchStyle={styles.inputSearchStyle}
                        // iconStyle={styles.iconStyle}
                        data={HomeSupport}
                        search={false}
                        maxHeight={300}
                        labelField="label"
                        valueField="value"
                        // placeholder="Allow day Booking"
                        // searchPlaceholder="5 guests"
                        value={HomeSupport}
                        onChange={item => {
                            setHomeSupport(item.label);
                        }} />
                    <Text style={{ marginHorizontal: 20, color: '#000', }}>What is the maximum number of people allowed for parties?</Text>
                    <TextInput multiline placeholder={'We allow minimum of 20 guests above for party and 10 guests for get together'} value={parties} onChangeText={(text) => setParties(text)} style={{
                        paddingLeft: 15,
                        height: 80,
                        margin: 10,
                        backgroundColor: color.appTextBackgoundColor,
                        borderRadius: 10,
                        marginLeft: 20,
                        marginRight: 20,
                        fontSize: 15,color:'#000'
                    }}placeholderTextColor={color.appTextColor} />

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
                </KeyboardAwareView>
                <View style={{}}>
                    <View style={{ padding: 20, backgroundColor: color.appTextBackgoundColor, flexDirection: 'row', justifyContent: 'space-between', marginTop: 20 }}>
                        <TouchableOpacity onPress={() => 
                            props.navigation.navigate('AddPropertyWelcome')
                            // props.navigation.navigate('AddPropertyOffer')
                            } style={{ backgroundColor: color.appBlueColor, borderRadius: 10 }}><Text style={{ fontSize: 18, color: color.appWhiteColor, marginHorizontal: 40, marginVertical: 20, fontWeight: '600' }}>Back</Text></TouchableOpacity>
                        <View>
                            <TouchableOpacity onPress={() => handleValidation()
                            }>
                                <LinearGradient colors={[color.appYellowColor, color.appOrangeColor]} style={styles.linearGradient, { borderRadius: 10 }} >
                                    <Text style={{ marginHorizontal: 40, marginVertical: 20, color: color.appWhiteColor, fontWeight: '600', fontSize: 18 }} >
                                        Next
                                    </Text>
                                </LinearGradient>
                            </TouchableOpacity>
                        </View>
                    </View>
                </View>
            </View>
        </View>
    )
}

const styles = StyleSheet.create({
    input: {
        height: 40,
        margin: 12,
        padding: 20,
        backgroundColor: color.appTextBackgoundColor,
        borderRadius: 4
    },
    container: {
        marginRight: 10, fontSize: 20, color: color.appWhiteColor, fontWeight: '600'
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
        alignSelf: "center",
        backgroundColor: 'transparent',
    },

});


export default AddPropertyLocated1;