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
import { KeyboardAwareView } from "react-native-keyboard-aware-view";


const AddPropertyDescription = (props, onChangeText,
    placeholder,
    value,
    isNumeric) => {
    const [EnterPrice, setEnterPrice] = useState('');
    const route = useRoute()



    // console.log('route', route.params.image);
    useEffect(() => {
        // console.log("userid propertyid title", route.params.userId, route.params.propertyId, route.params.categoryId,

        //     route.params.address, route.params.latitude, route.params.longitude, route.params.countryId,
        //     route.params.provinceId, route.params.cityId, route.params.areaId,
        //     route.params.postalCode, route.params.streetName,
        //     route.params.streetType, route.params.selectFloor, route.params.stairs,
        //     route.params.elevator, route.params.apertNo, route.params.beds, route.params.bedrooms, route.params.bathrooms,
        //     route.params.kitchen, "amenity", route.params.amenityId,
        //     route.params.maxGuest, route.params.petsAllow, route.params.numberOfNights, route.params.cctv, route.params.cameraLocation,
        //     route.params.wifiUserName, route.params.wifiPassword, route.params.television, route.params.televisionLocation,
        //     route.params.allowDayBooking,
        //     route.params.houseRules, route.params.responseTime, route.params.title)

// if(EnterPrice.length > 500){
//     alert('limit over')
// }

        // getAllCountryApi()
        // getAllCountryCodeApi()


    }, [])
    const handleValidation = () => {
        if (EnterPrice == "") {
            Toast.show({
                type: 'error',
                text1: "Shortlet",
                text2: "Please Enter Description",


            })
        }

        else {
            props.navigation.navigate('AddPropertyNight',
                {
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
                    houseRules: route.params.houseRules, responseTime: route.params.responseTime,
                    image: "",
                    imageUI: route?.params?.image,
                    title: route.params.title,
                    description: EnterPrice
                }
            )
        }
    }




    return (
        <View style={commonStyles.container}>

            <Header props={props} Heading={'Add Property'} onPress={() => props.navigation.goBack()} />
            <KeyboardAwareView>
                <ScrollView showsVerticalScrollIndicator={false}>
            <View style={{ marginTop: 20, marginHorizontal: 20 }}>
                <Text style={{ fontSize: 18, fontWeight: '600', color: color.primaryColorBlack }}>Create your description</Text>
                <View style={{ backgroundColor:color.appTextBackgoundColor, width: '100%',
                 height: height / 3,
                  borderRadius: 8, marginTop: 10 }}>
                    <TextInput  style={styles.TextVie}
                        placeholder={'Create your description'}
                        value={EnterPrice}
                        onChangeText={(e) => setEnterPrice(e)}
                        multiline
                        scrollEnabled={true}
                        textAlignVertical='top'
                        placeholderTextColor={color.appTextColor}
                        // maxLength={500}

                    />
                </View>
                {/* <Text style={{ textAlign: "right", color: color.primaryColorBlack, marginTop: 5 }}>{EnterPrice?.length}/500</Text> */}
            </View>

          
            <View style={{ marginTop:20, justifyContent: 'flex-end', }}>
                <View style={{ padding: 20, backgroundColor: color.appTextBackgoundColor, flexDirection: 'row', justifyContent: 'space-between', }}>
                    <TouchableOpacity onPress={() => props.navigation.goBack()} style={{ backgroundColor: color.appBlueColor, borderRadius: 10 }}>
                        <Text style={{ fontSize: 16, color: color.appWhiteColor, marginHorizontal: 40, marginVertical: 20, fontWeight: '600' }}>Back</Text></TouchableOpacity>
                    <View>
                        <TouchableOpacity onPress={() => handleValidation()}>
                            <LinearGradient colors={[color.appYellowColor, color.appOrangeColor]} style={[styles.linearGradient, { borderRadius: 10 }]} >
                                <Text style={{ marginHorizontal: 40, marginVertical: 20, color: color.appWhiteColor, fontWeight: '600', fontSize: 18 }} >
                                    Next
                                </Text>
                            </LinearGradient>
                        </TouchableOpacity>
                    </View>
                </View>
            </View>
            </ScrollView>

            </KeyboardAwareView>
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
        // paddingLeft: 15,
        // paddingRight: 15,

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


    TextVie: {
        // paddingLeft: 15,
        // height:height/4,
        margin: 10,
        // backgroundColor: color.appTextBackgoundColor,
        borderRadius: 10,
        marginLeft: 20,
        marginRight: 20,
        fontSize: 15,color:'#000',
        textAlign:'justify'
        // backgroundColor:'blue',
        
        
    },


});

export default AddPropertyDescription;