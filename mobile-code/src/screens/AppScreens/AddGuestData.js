import React, { useContext, useEffect, useState } from "react";
import { View, Image, Text, ImageBackground, TouchableOpacity, _Text, ScrollView } from "react-native";
import { color, height, width } from "../../styles/colors";
import Images from "../../styles/Images";
import { commonStyles } from "./../../styles/style";
import { Colors, colors } from "react-native/Libraries/NewAppScreen";
import Header from "../../components/Header";
import TextView from "../../components/TextView";
import { Dropdown } from "react-native-element-dropdown";
import AsyncStorage from "@react-native-async-storage/async-storage";
import { Context as BookingContext } from '../../context/BookingContext'
import { useFocusEffect, useRoute } from "@react-navigation/native";
import { TextInput } from "react-native";
import { Toast } from "react-native-toast-message/lib/src/Toast";
import { BackHandler } from "react-native";
import { KeyboardAwareView } from "react-native-keyboard-aware-view";

const AddGuestData = (props) => {
    const [value, setValue] = useState(null);
    // const [mobileNumber, setMobileNumber] = useState('');
    // const [conformPassword, SetPassword] = useState('');
    const [fullName, setFullName] = useState('');
    const [lastName, setLastName] = useState('')
    const [city, setCity] = useState('');
    const [postalCode, setPostalCode] = useState('');
    const [country, setCountry] = useState('');
    const [phoneNumber, setPhoneNumber] = useState('');
    const [email, setEmail] = useState('');
    const [addData, setAddData] = useState([])
    const [placeholder, setPlaceHolder] = useState('Country')
    const route = useRoute()

    const [propertyId, setPropertyId] = useState(route.params.propertyId)
    const { propertyid, price, convenceFee, myId, max_Gest,loyalty_points,points_amount } = route.params
    const { getAllCountryApi, updateBookingData, state: { bookingData } } = useContext(BookingContext)

    useEffect(() => {
        getAllCountryApi()
    }, [props])

    const handler = () => {
        props.navigation.navigate('Booking', {
            propertyId: route.params.propertyid, max_Gest: max_Gest, bookType: route?.params?.book, price,loyalty_points:loyalty_points,points_amount:points_amount
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

    const addGuestDataValidation = () => {
        if (fullName == '') {
            Toast.show({
                type: 'error',
                text1: 'Shortlet',
                text2: 'Please enter full name'
            })
        } else if (lastName == '') {
            Toast.show({
                type: 'error',
                text1: 'Shortlet',
                text2: 'Please enter last name'
            })
        } else if (city == '') {
            Toast.show({
                type: 'error',
                text1: 'Shortlet',
                text2: 'Please enter city'
            })
        } else if (postalCode == '') {
            Toast.show({
                type: 'error',
                text1: 'Shortlet',
                text2: 'Please enter postal code'
            })

        } else if (postalCode.length < 6) {
            Toast.show({
                type: 'error',
                text1: 'Shortlet',
                text2: 'Please enter 6 digit postal code'
            })
        } else if (placeholder == 'Country') {
            Toast.show({
                type: 'error',
                text1: 'Shortlet',
                text2: 'Please select Country'
            })
        } else if (phoneNumber == '') {
            Toast.show({
                type: 'error',
                text1: 'Shortlet',
                text2: 'Please enter phone number'
            })
        } else if (phoneNumber !== "") {
            let re = /^([+]\d{2})?\d{8,12}$/
            if (re.test(phoneNumber) === false) {
                Toast.show({
                    type: 'error',
                    text1: 'Shortlet',
                    text2: 'Invalid mobile number'
                })
            }

            else if (email == '') {
                Toast.show({
                    type: 'error',
                    text1: 'Shortlet',
                    text2: 'Please enter email'
                })
            } else if (email !== "") {
                let reg = /^(([^<>()[\]\.,;:\s@\"]+(\.[^<>()[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/i

                if (reg.test(email) === false) {
                    Toast.show({
                        type: 'error',
                        text1: 'Shortlet',
                        text2: 'Invalid e-mail address.'
                    })
                }

                else {
                    let temp = { fullName, lastName, city, postalCode, phoneNumber, value, email }
                    updateBookingData({ type: "guestData", data: temp }, () => props.navigation.navigate('Booking', {
                        propertyId: route.params.propertyid, propertyid, price, convenceFee, myId, max_Gest: max_Gest,
                        // bookType: 'Reserve'
                        bookType: route?.params?.book,
                        loyalty_points:loyalty_points,
                        points_amount:points_amount
                    }))
                    // let temp = { fullName, lastName, city, postalCode, phoneNumber, value, email }
                    // updateBookingData({ type: "guestData", data: temp }, () => props.navigation.navigate('Booking', {  id: route.params.propertyid }))
                }
            }
        }
    }

    const onInputChange = (value) => {

        console.log('Input value: ', value);
        const re = /^[a-zA-Z\s]*$/;
        if (value === "" || re.test(value)) {
          setFullName(value);
        }
      }
    // console.log('=====dddd',addData);
    // console.log('==== add guest', fullName, city, lastName, postalCode, phoneNumber, email, value)
    return (
        <View style={commonStyles.container}>
            <Header props={props} Heading={'Add Guests Data'} onPress={() => props.navigation.navigate('Booking', {
                propertyId: route.params.propertyid, max_Gest: max_Gest, bookType: route?.params?.book, price,loyalty_points:loyalty_points,points_amount:points_amount
            })} />
            <KeyboardAwareView>
            <ScrollView contentContainerStyle={{ marginTop: 10 }}>
                <TextView
                    placeholder={'Full Name'}
                    value={fullName}
                    onChangeText={onInputChange}
                />
                <TextView
                    placeholder={'Last Name'}
                    value={lastName}
                    onChangeText={(e) => setLastName(e)}
                />
                <TextView
                    placeholder={'City'}
                    value={city}
                    onChangeText={(e) => setCity(e)}
                />
                <TextView
                    placeholder={'Postal Code'}
                    value={postalCode}
                    onChangeText={(e) => setPostalCode(e.replace(/[^0-9]/g, ''))}

                    isNumeric={true}
                    maxLength={6}
                // isNumeric={true}
                />
                {/* <TextView
                    placeholder={'Country'}
                    value={conformPassword}
                    onChangeText={(e) => SetPassword(e)}
                    isNumeric={true}
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
                        paddingRight: 15
                    }}
                    placeholderStyle={color.appTextColor}
                    selectedTextProps={{ style: { color: '#000' } }}
                    itemTextStyle={{ color: '#000' }}
                    // inputSearchStyle={styles.inputSearchStyle}
                    // iconStyle={styles.iconStyle}
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
                    }} />
                <TextView
                    placeholder={'Phone Number'}
                    value={phoneNumber}
                    onChangeText={(e) => setPhoneNumber(e.replace(/[^0-9]/g, ''))}
                    isNumeric={true}
                    maxLength={12}
                />

                <TextInput
                    placeholder={'E-Mail'}
                    value={email}
                    onChangeText={(e) => setEmail(e)}
                    style={{
                        paddingLeft: 15,
                        height: 50,
                        margin: 10,
                        backgroundColor: color.appTextBackgoundColor,
                        borderRadius: 10,
                        marginLeft: 20,
                        marginRight: 20,
                        fontSize: 15, color: '#000'
                    }}
                    keyboardType='email-address'
                    placeholderTextColor={color.appTextColor}
                />
                <TouchableOpacity
                    onPress={() => addGuestDataValidation()}
                    // onPress={()=>props.navigation.navigate('Home')}
                    style={{
                        marginTop: 10,
                        backgroundColor: color.appOrangeColor,
                        width: width - 30,
                        padding: 10,
                        borderRadius: 10,
                        justifyContent: 'center',
                        marginLeft: 15,
                        height: 50,
                        marginBottom: 40
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
export default AddGuestData;

