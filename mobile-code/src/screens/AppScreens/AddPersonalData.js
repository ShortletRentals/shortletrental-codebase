

import React, { useEffect, useState } from "react";
import CheckBox from '@react-native-community/checkbox';
import { View, Image, Text, ImageBackground, TouchableOpacity, _Text, ScrollView, FlatList, TextInput } from "react-native";
import { color, height, width } from "../../styles/colors";
import Images from "../../styles/Images";
import { commonStyles } from "./../../styles/style";
import { Colors, colors } from "react-native/Libraries/NewAppScreen";
import Header from "../../components/Header";
import TextView from "../../components/TextView";
import { Dropdown } from "react-native-element-dropdown";
import { useContext } from "react";
import { Context as BookingContext } from '../../context/BookingContext'
import { Context as AuthContext } from '../../context/AuthContext'
import { Booking, getAllCountry } from "../../network/Webconstant";
import axios from "axios";
import AsyncStorage from "@react-native-async-storage/async-storage";
import { useFocusEffect, useRoute } from "@react-navigation/native";
import FlashMessage, { showMessage } from "react-native-flash-message";
import { Toast } from "react-native-toast-message/lib/src/Toast";
import { BackHandler } from "react-native";
import { KeyboardAwareView } from "react-native-keyboard-aware-view";
const data = [
    { label: 'India', value: '1' },
    { label: 'Us', value: '2' },
    { label: 'England', value: '3' },
    { label: 'Uk', value: '4' },
    { label: 'Russia', value: '5' },
    // { label: '', value: '6' },
    // { label: '', value: '7' },
    // { label: '', value: '8' },
];
const agency = [
    { label: 'Yes', value: '1' },
    { label: 'No', value: '2' },
]
const AddPersonalData = (props) => {
    const route = useRoute()

    const { propertyid, price, convenceFee, myId, max_Gest,loyalty_points,points_amount } = route?.params
    const [fullName, setFullName] = useState('');
    const [Address, SetAddress] = useState('');
    const [City, SetCity] = useState('');
    const [PostalCode, SetPostalCode] = useState('');
    const [Country, SetCountry] = useState('');
    const [PhoneNumber, SetPhoneNumber] = useState('');
    const [Email, SetEmail] = useState('');
    const [Comments, SetComments] = useState('');
    const [AreYouAgency, SetAreYouAgency] = useState('');
    const [toggleCheckBox, setToggleCheckBox] = useState(true)
    const [toggleCheckBox1, setToggleCheckBox1] = useState(false)
    const [value, setValue] = useState(null);
    const [value1, setValue1] = useState(null);
    const [token, setToken] = useState('')
    const [placeholder, setPlaceHolder] = useState('Country')
    const [propertyId, setPropertyId] = useState(route.params.propertyId)
    const { getAllCountryApi, updateBookingData,
        state: { bookingData }
    } = useContext(BookingContext)
    const { state: { Token_ID } } = useContext(AuthContext)



    // console.log("====-=-=-=-=-=-undefined", placeholder)

    useEffect(() => {
        // console.log("====-=-=-=-=-=-", route.params.propertyid)
        getAllCountryApi()
    }, [props])

    const handler = () => {
        props.navigation.navigate('Booking', {
            propertyid: route?.params?.propertyid, max_Gest: max_Gest, bookType: route?.params?.book, price,loyalty_points:loyalty_points,points_amount:points_amount
        })
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
    let reg =
        /^([A-z0-9!@#$%^&*().,<>{}[\]<>?_=+\-|;:\'\"\/])*[^\s]\1*$/
    // /^(\d{3})[- ]?(\d{3})[- ]?(\d{4})$/

    // console.log('=====fill',fillData)

    const onInputChange = (value) => {

        console.log('Input value: ', value);
        const re = /^[a-zA-Z\s]*$/;
        if (value === "" || re.test(value)) {
          setFullName(value);
        }
      }
    const onInputChangeCity = (value) => {

        console.log('Input value: ', value);
        const re = /^[a-zA-Z\s]*$/;
        if (value === "" || re.test(value)) {
          SetCity(value);
        }
      }
    const addDataValidation = () => {
        if (fullName == '') {
            Toast.show({
                type: 'error',
                text1: 'Shortlet',
                text2: 'please enter full name'
            })
        } else if (Address == '') {
            Toast.show({
                type: 'error',
                text1: 'Shortlet',
                text2: 'please enter address'
            })
        } else if (City == '') {
            Toast.show({
                type: 'error',
                text1: 'Shortlet',
                text2: 'please enter city'
            })
        } else if (PostalCode == '') {
            Toast.show({
                type: 'error',
                text1: 'Shortlet',
                text2: 'please enter postal code'
            })
        }
        else if (PostalCode.length < 6) {
            Toast.show({
                type: 'error',
                text1: 'Shortlet',
                text2: 'please enter 6 digit postal code'
            })
        }
        else if (placeholder == 'Country') {
            Toast.show({
                type: 'error',
                text1: 'Shortlet',
                text2: 'please select country'
            })
        }
        else if (PhoneNumber == '') {
            Toast.show({
                type: 'error',
                text1: 'Shortlet',
                text2: 'please enter phone number'
            })
        }
        // else if (PhoneNumber.length < 10) {
        //     Toast.show({
        //         type: 'error',
        //         text1: 'Shortlet',
        //         text2: 'please enter 10 digit'
        //     })

        // }
        else if (PhoneNumber !== "") {
            let re = /^([+]\d{2})?\d{8,12}$/
            if (re.test(PhoneNumber) === false) {
                Toast.show({
                    type: 'error',
                    text1: 'Shortlet',
                    text2: 'Invalid mobile number'
                })
            }
            else if (Email == '') {
                Toast.show({
                    type: 'error',
                    text1: 'Shortlet',
                    text2: 'please enter email'
                })
            } else if (Email !== "") {
                let reg = /^(([^<>()[\]\.,;:\s@\"]+(\.[^<>()[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/i

                if (reg.test(Email) === false) {
                    Toast.show({
                        type: 'error',
                        text1: 'Shortlet',
                        text2: 'Invalid e-mail address.'
                    })
                }
               
                else if (value1 == null) {
                    Toast.show({
                        type: 'error',
                        text1: 'Shortlet',
                        text2: 'please select agency'
                    })
                } else {
                    let fillData = { fullName, Address, City, PostalCode, PhoneNumber, value, Email, Comments, book_for: toggleCheckBox ? "self" : "other", value1 }
                    // console.log('====tempdata', fillData);
                    updateBookingData({ type: "personalData", data: fillData }, () => props.navigation.navigate('Booking', {
                        propertyId: route.params.propertyid, propertyid, price, convenceFee, myId, max_Gest: max_Gest,
                        // bookType: 'Reserve',
                        bookType: route?.params?.book,
                        loyalty_points:loyalty_points,
                        points_amount:points_amount
                    }))
                    setPlaceHolder('Country')
                    // setFullName()
                    // SetAddress()
                    // SetCity()
                    // SetPostalCode()
                    // setValue()
                    // SetEmail()
                    // SetComments()
                    // SetPhoneNumber()
                    // setValue1()

                }
            }
        }
    }
    // console.logdatallName,Address,City,PostalCode,PhoneNumber,value,Email,Comments,value)
    return (
        <View style={commonStyles.container}>
            <Header onPress={() => props.navigation.navigate('Booking', {
                propertyid: route?.params?.propertyid, max_Gest: max_Gest, bookType: route?.params?.book, price,loyalty_points:loyalty_points,points_amount:points_amount
            })} props={props} Heading={'Add Personal Data'} />
            <KeyboardAwareView>
            <ScrollView contentContainerStyle={{ flexGrow: 1, marginTop: 10 }}>
                <TextView
                    placeholder={'Full Name'}
                    value={fullName}
                    onChangeText={onInputChange}
                />
                <FlashMessage position={'center'} />
                <TextView
                    placeholder={'Address'}
                    value={Address}
                    onChangeText={(e) => SetAddress(e)}
                />
                <TextView
                    placeholder={'City'}
                    value={City}
                    onChangeText={onInputChangeCity}
                />
                <TextView
                    // ref='mobileNo'
                    placeholder={'Postal Code'}
                    value={PostalCode}
                    onChangeText={(text) => SetPostalCode(text.replace(/[^0-9]/g, ''))}
                    isNumeric={true}
                    maxLength={6}

                />
                {/* <TextView
                    placeholder={'Country'}
                    value={Country}
                    onChangeText={(e) => SetCountry(e)}
                    isNumeric={true}
                /> */}
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
                        paddingRight: 12
                    }}
                    placeholderStyle={{ color: color.appTextColor }}
                    itemTextStyle={{ color: '#000' }}
                    selectedTextProps={{ style: { color: '#000' } }}
                    data={bookingData.country || []}
                    search={true}
                    maxHeight={300}
                    labelField="label"
                    valueField="value"
                    placeholder="Country"
                    searchPlaceholder="Search Country"
                    value={bookingData.label}
                    onChange={item => {
                        setValue(item.label);
                        setPlaceHolder(item.label)
                    }}
                />
                <TextView
                    placeholder={'Phone Number'}
                    value={PhoneNumber}
                    onChangeText={(e) => SetPhoneNumber(e.replace(/[^0-9]/g, ''))}
                    isNumeric={true}
                    maxLength={12}
                />

                <TextInput
                    placeholder={'E-Mail'}
                    value={Email}
                    onChangeText={(e) => SetEmail(e)}
                    style={{
                        paddingLeft: 15,
                        height: 50,
                        margin: 10,
                        backgroundColor: color.appTextBackgoundColor,
                        borderRadius: 10,
                        marginLeft: 20,
                        marginRight: 20,
                        fontSize: 15,
                        color: '#000'
                    }}
                    placeholderTextColor={color.appTextColor}
                    keyboardType='email-address'
                    
                />
                <TextInput
                    placeholder={'Comments'}
                    value={Comments}
                    maxLength={100}
                    onChangeText={(e) => SetComments(e)}
                    style={{
                        paddingHorizontal: 15,
                        height: 50,
                        margin: 10,
                        backgroundColor: color.appTextBackgoundColor,
                        borderRadius: 10,
                        marginLeft: 20,
                        marginRight: 20,
                        fontSize: 15,
                        color: '#000'
                    }}
                />
                <View style={{ flexDirection: 'row', marginTop: 10, marginLeft: 20 }}>
                    {/* <CheckBox
                        // style={{ width: 20, height: 20 }}
                        disabled={false}
                        value={toggleCheckBox}
                        onValueChange={(newValue) => setToggleCheckBox(newValue)}
                        tintColors={{ true: '#F15927', false: '#C1C1C1' }}
                        // onFillColor='#007aaf'
                        onCheckColor='red'
                        style={{ height: 20, width: 20, transform: [{ scaleX: 1.2 }, { scaleY: 1.2 }], borderRadius: 10 }}
                    /> */}
                    <TouchableOpacity onPress={() => [setToggleCheckBox(!toggleCheckBox), setToggleCheckBox1(!toggleCheckBox1)]} >
                        <Image source={toggleCheckBox ? Images.radioActivesIcon : Images.radioinactiveIcon} style={{ height: 22, width: 22 }} />
                    </TouchableOpacity>
                    <Text style={{ marginHorizontal: 12, fontSize: 15, color: '#000' }}>I am the main guest</Text>
                </View>
                <View style={{ flexDirection: 'row', marginTop: 10, marginLeft: 20 }}>
                    <TouchableOpacity onPress={() => [setToggleCheckBox1(!toggleCheckBox1), setToggleCheckBox(!toggleCheckBox)]}>
                        <Image source={toggleCheckBox1 ? Images.radioActivesIcon : Images.radioinactiveIcon} style={{ height: 22, width: 22 }} />
                    </TouchableOpacity>
                    {/* <CheckBox 
                    // style={{ width: 20, height: 20 }}
                        disabled={false}
                        value={toggleCheckBox1}
                        onValueChange={(newValue) => setToggleCheckBox1(newValue)}
                        tintColors={{ true: '#F15927', false: '#C1C1C1' }}
                        // onFillColor='#007aaf'
                        onCheckColor='red'
                        style={{ height: 20, width: 20, transform: [{ scaleX: 1.2 }, { scaleY: 1.2 }], borderRadius: 10 }}
                    /> */}
                    <Text style={{ marginHorizontal: 12, fontSize: 15, color: '#000' }}>Want to book for someone else </Text>
                </View>
                {/* <TextView
                    placeholder={'Are you an agency?'}
                    value={AreYouAgency}
                    onChangeText={(e) => SetAreYouAgency(e)}
                    isNumeric={true}
                /> */}
                <Dropdown
                    style={{
                        // height:40,
                        // marginHorizontal:20,
                        paddingLeft: 15,
                        paddingRight: 15,
                        height: 50,
                        margin: 10,
                        backgroundColor: color.appTextBackgoundColor,
                        borderRadius: 10,
                        marginLeft: 20,
                        marginRight: 20,
                        fontSize: 15,
                        marginTop: 20
                    }}
                    placeholderStyle={{ color: color.appTextColor }}
                    itemTextStyle={{ color: '#000' }}
                    selectedTextProps={{ style: { color: '#000' } }}
                    data={agency || []}
                    search={false}
                    maxHeight={300}
                    labelField="label"
                    valueField="value"
                    placeholder="Are you an agency?"
                    // searchPlaceholder="5 guests"
                    value={agency.label}
                    onChange={item =>
                        setValue1(item.label)
                    } />
                <TouchableOpacity
                    onPress={() => addDataValidation()}
                    style={{
                        marginTop: 10,
                        backgroundColor: color.appOrangeColor,
                        width: width - 30,
                        padding: 10,
                        borderRadius: 10,
                        justifyContent: 'center',
                        marginLeft: 15,
                        marginBottom: 50,
                        height: 50
                    }}>
                    <View style={{
                        position: 'absolute',
                        flex: 1,
                    }}>
                        <Image source={Images.whiteDot} style={{
                            paddingLeft: 70,
                            width: 25, height: 25, resizeMode: 'contain',
                        }} />
                    </View>
                    <Text style={{ textAlign: "center", fontSize: 15, color: color.appWhiteColor }}>Continue</Text>
                </TouchableOpacity>
            </ScrollView>
            </KeyboardAwareView>
        </View>

    )
}
export default AddPersonalData;

