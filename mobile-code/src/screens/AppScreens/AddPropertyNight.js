import React, { useState, useEffect } from "react";
import { View, Image, Text, ImageBackground, StyleSheet, TextInput, TouchableOpacity, _Text, ScrollView } from "react-native";
import { color, height, width } from "../../styles/colors";
import Images from "../../styles/Images";
import { commonStyles } from "./../../styles/style";
import { Colors, colors } from "react-native/Libraries/NewAppScreen";
import Header from "../../components/Header";
import TextView from "../../components/TextView";
import LinearGradient from "react-native-linear-gradient";
import { useIsFocused, useNavigation, useRoute } from "@react-navigation/native";
import { Toast } from "react-native-toast-message/lib/src/Toast";
import axios from "axios";
import { max } from "moment";
import { getPropertyMinMaxPrice } from '../../network/Webconstant';
import { BackHandler } from "react-native";




const AddPropertyNight = (props) => {
    const [EnterPrice, setEnterPrice] = useState('');
    const [minPrice, setMinPrice] = useState('');
    const [maxPrice, setMaxPrice] = useState('');
    const [partyCommission,setPartyCommission] = useState('')

    const route = useRoute()

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
       



        axios({
            method: "post",
            url: getPropertyMinMaxPrice,
            data: {
                country_id: route?.params?.countryId,
                province_id: route?.params?.provinceId,
                city_id: route?.params?.cityId,
                area_id: route?.params?.areaId
            }
            // headers: { "content-type": "application/x-www-form-urlencoded" }
        })
            .then((response) => {
                if (response?.data?.status == true) {
                    setMinPrice(response?.data?.min_price)
                    setMaxPrice(response?.data?.max_price)
                  

                } else {
                    console.log("api error", response);
                }
            })
            .catch((e) => {
            });



    }, [])

    const handleValidation = () => {
        if (EnterPrice == "") {
            Toast.show({
                type: 'error',
                text1: "Shortlet",
                text2: "Please enter price",


            })
        } else {
            props.navigation.navigate('AddPropertyPlace', {
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
                maxGuest: route.params.maxGuest, petsAllow: route.params.petsAllow, numberOfNights: route.params.numberOfNights, cctv: route.params.cctv,
                cameraLocation: route.params.cameraLocation,
                wifiUserName: route.params.wifiUserName, wifiPassword: route.params.wifiPassword, television: route.params.television,
                televisionLocation: route.params.televisionLocation,
                allowDayBooking: route.params.allowDayBooking,
                houseRules: route.params.houseRules, responseTime: route.params.responseTime, title: route.params.title,
                description: route.params.description, price: EnterPrice, image: route?.params?.imageUI,partyCommission:partyCommission
            })
        }
    }



    return (
        <View style={commonStyles.container}>

            <Header props={props} Heading={'Add Property'} onPress={() => props.navigation.goBack()} />
            <View style={{ marginTop: 20 }}>
                <Text style={{ marginHorizontal: 20, fontSize: 17, fontWeight: '600', color: color.primaryColorBlack }}>Price per night</Text>
                {/* <TextView
                    placeholder={'Enter Price'}
                    value={EnterPrice}
                    onChangeText={(e) => setEnterPrice(e)}
                    
                /> */}
                <TextInput
                    placeholder={'Enter Price'}
                    value={EnterPrice}
                    onChangeText={(e) => setEnterPrice(e.replace(/[^0-9]/g, ''))}
                    style={{
                        paddingLeft: 15,
                        height: 50,
                        margin: 10,
                        backgroundColor: color.appTextBackgoundColor,
                        borderRadius: 10,
                        marginLeft: 20,
                        marginRight: 20,
                        fontSize: 15,
                        textAlign: 'center',color:'#000'
                    }}placeholderTextColor={color.appTextColor}
                    keyboardType='numeric'
                />
                <Text style={{ textAlign: 'center', fontWeight: '600', color: color.appTextColor, fontSize: 16 }}>per night</Text>
                <Text numberOfLines={3} style={{ marginHorizontal: 40, textAlign: 'center', marginTop: 10, color: color.primaryColorBlack, fontSize: 17, fontWeight: '400', alignSelf: 'center' }}>Keep in mind that places like yours usually range from NGN {minPrice == null ? "0.0" : minPrice} to {maxPrice == null ? "0.0" : maxPrice}</Text>
                <Text style={{ marginHorizontal: 20, fontSize: 17, fontWeight: '600', color: color.primaryColorBlack, marginVertical: 5 }}>What is your party rate</Text>
                <TextInput
                    placeholder={'Party rate'}
                    value={partyCommission}
                    onChangeText={(e) => setPartyCommission(e.replace(/[^0-9]/g, ''))}
                    style={{
                        paddingLeft: 15,
                        height: 50,
                        margin: 10,
                        backgroundColor: color.appTextBackgoundColor,
                        borderRadius: 10,
                        marginLeft: 20,
                        marginRight: 20,
                        fontSize: 15,
                        textAlign: 'center',color:'#000'
                    }}
                    keyboardType='numeric'
                    placeholderTextColor={color.appTextColor}
                />
            </View>
            {/* <View style={{ flex: 1, justifyContent: 'flex-end', marginBottom: 30 }}>
                    <View style={{ backgroundColor: color.appTextBackgoundColor, flexDirection: 'row', justifyContent: 'space-between', marginHorizontal: 15, marginTop: 20 }}>
                        <TouchableOpacity onPress={()=>props.navigation.navigate('AddPropertyDescription')}style={{ backgroundColor: color.appBlueColor, borderRadius: 10 }}><Text style={{ fontSize: 18, color: color.appWhiteColor, marginHorizontal: 40, marginVertical: 20, fontWeight: '600' }}>Back</Text></TouchableOpacity>
                        <TouchableOpacity onPress={()=>props.navigation.navigate('AddPropertyPlace')}>
                            <LinearGradient colors={[color.appYellowColor, color.appOrangeColor]} style={styles.linearGradient, { borderRadius: 10 }}>
                                <Text style={{ marginHorizontal: 40, marginVertical: 20, color: color.appWhiteColor, fontWeight: '600', fontSize: 18 }} >
                                    Next
                                </Text>
                            </LinearGradient>
                        </TouchableOpacity>
                    </View>
                </View> */}
            <View style={{  }}>
                <View style={{ padding: 20, backgroundColor: color.appTextBackgoundColor, flexDirection: 'row', justifyContent: 'space-between', }}>
                    <TouchableOpacity onPress={() =>
                        props.navigation.goBack()
                        //  props.navigation.navigate('AddPropertyDescription')
                    } style={{ backgroundColor: color.appBlueColor, borderRadius: 10 }}>
                        <Text style={{ fontSize: 16, color: color.appWhiteColor, marginHorizontal: 40, marginVertical: 20, fontWeight: '600' }}>Back</Text></TouchableOpacity>
                    <View>
                        <TouchableOpacity onPress={() => handleValidation()}>
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
        marginRight: 10, fontSize: 20, color: color.appWhiteColor, fontWeight: '600',
    },
    AddingBackground: { height: 30, width: 30, borderRadius: 15, backgroundColor: color.appTextBackgoundColor, alignItems: 'center', justifyContent: 'center' },

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

export default AddPropertyNight;