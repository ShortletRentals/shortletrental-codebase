import React, { useState, useEffect } from "react";
import {
    View, Image, Text, ImageBackground, StyleSheet, TextInput,
    TouchableOpacity, _Text, ScrollView, FlatList,
    ActivityIndicator
} from "react-native";
import { color, height, width } from "../../styles/colors";
import Images from "../../styles/Images";
import { commonStyles } from "./../../styles/style";
import { Colors, colors } from "react-native/Libraries/NewAppScreen";
import Header from "../../components/Header";
import TextView from "../../components/TextView";
import LinearGradient from "react-native-linear-gradient";
import { useIsFocused, useNavigation, useRoute } from "@react-navigation/native";
import AsyncStorage from '@react-native-async-storage/async-storage';
import { Toast } from "react-native-toast-message/lib/src/Toast";
import axios from "axios";
import {


    Circle,
    Svg,
    SvgUri,
} from 'react-native-svg';
import { getAllAmenities } from '../../network/Webconstant';
import { BackHandler } from "react-native";







let amenityId = ""


const AddPropertyOffer = (props) => {
    const route = useRoute()

    const [select, setSelect] = useState(null)
    const [propData, setPropData] = useState([])
    const [loader, setLoader] = useState(false);
    const [selectedArr, setSelectedArr] = useState([])

    useEffect(() => {
        setLoader(true)
        axios({
            method: "get",
            url: getAllAmenities,
            // headers: { "content-type": "application/x-www-form-urlencoded" }
        })
            .then((response) => {
                if (response?.data?.status == true) {
                    setLoader(false)

                    setPropData(response.data.data)
                   

                } else {
                    console.log("api error", response);
                    setLoader(false)

                }
            })
            .catch((e) => {
                setLoader(false)

            });

    }, [])

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
    const handleValidation = () => {
        if (selectedArr == '') {
            Toast.show({
                type: 'error',
                text1: "Shortlet",
                text2: "Please select what your place has to offer",


            })
        } else {
            props.navigation.navigate('AddPropertyLocated1'
                , {
                    propertyId: route.params.propertyId, userId: route.params.userId,
                    categoryId: route.params.categoryId,
                    address: route.params.address, latitude: route.params.latitude, longitude: route.params.longitude,
                    countryId: route.params.countryId,
                    provinceId: route.params.propertyId, cityId: route.params.cityId, areaId: route.params.areaId,
                    postalCode: route.params.postalCode, streetName: route.params.streetName,
                    streetType: route.params.streetType, selectFloor: route.params.selectFloor, stairs: route.params.stairs,
                    elevator: route.params.elevator, apertNo: route.params.apertNo, beds: route.params.beds, bedrooms: route.params.bedrooms,
                    bathrooms: route.params.bathrooms, kitchen: route.params.kitchen,
                    //  amenityId: selectedArr
                    amenityId: selectedArr
                }
            )

            // props.navigation.navigate('AddPropertyPhotos')

        }
    }

    // console.log('========', selectedArr)
    const selectAction = (item, state) => {
        let arr = [...selectedArr]
        if (state) {
            const index = arr.indexOf(item?.id)
            if (index > -1) { // only splice array when item is found
                arr.splice(index, 1); // 2nd parameter means remove one item only
            }
        } else {
            arr?.push(item?.id)
        }
        setSelectedArr(arr)
    }
    const renderItem = ({ item, index }) => {
        // console.log('-----------------item addproperty offer', item);
        const state = selectedArr.includes(item?.id)
        return (
            <TouchableOpacity onPress={() => selectAction(item, state)
                // [ setSelect(index),amenityId=item.id]
            } style={[styles.flatView, { backgroundColor: state ? color.white : '#F5F7FA', borderColor: state ? color.appYellowColor : '#EFEFEF' }]}>
                <View style={{ marginHorizontal: 20 }}>
                    {/* <Image source={item.images} style={{ alignSelf: 'center' }} /> */}

                    {

                        // console.log('---------------kkkk',item.image.match('svg') == 'svg')
                        item.image.match('svg') == "svg" ?
                            <Svg style={{ height: 34, width: 50, resizeMode: "cover", borderTopRightRadius: 5, borderBottomRightRadius: 5, alignSelf: "center" }} >
                                <SvgUri
                                    width="100%"
                                    height="100%"
                                    uri={item?.image}
                               
                                />
                            </Svg>
                            :
                          
                            <View>
                                <Image source={{ uri: item?.image }} style={{ height: 40, width: 40, alignSelf: 'center' }} />
                            </View>
                    }
                    <Text numberOfLines={2} style={{ width: width / 3, alignSelf: 'center', textAlign: 'center', marginVertical: 8, fontSize: 15, fontWeight: '500', color: '#000' }}>{item.name}</Text>
                </View>
            </TouchableOpacity>
        )
    }







    return (
        <View style={commonStyles.container}>

            <Header props={props} Heading={'Add Property'} onPress={() => props.navigation.goBack()} />
            <Text style={{ marginTop: 20, fontSize: 17, fontWeight: '500', marginHorizontal: 20, color: color.primaryColorBlack }}>Should be Let guests know what your place has to offer</Text>
            {
                loader ? (
                    <View style={{ justifyContent: 'center', marginVertical: "40%", }}>
                        <ActivityIndicator size="large" color="#F99428" />
                    </View >
                ) :
                    <FlatList
                        data={propData}
                        numColumns={2}
                        renderItem={renderItem}
                        keyExtractor={(item, index) => index.toString()}
                    />
            }

            <View style={{ marginTop: 10 }}>
                <View style={{ padding: 20, backgroundColor: color.appTextBackgoundColor, flexDirection: 'row', justifyContent: 'space-between', }}>
                    <TouchableOpacity onPress={() => props.navigation.goBack()} style={{ backgroundColor: color.appBlueColor, borderRadius: 10 }}>
                        <Text style={{ fontSize: 16, color: color.appWhiteColor, marginHorizontal: 40, marginVertical: 20, fontWeight: '600' }}>Back</Text></TouchableOpacity>
                    <View>
                        <LinearGradient colors={[color.appYellowColor, color.appOrangeColor]} style={[styles.linearGradient, { borderRadius: 10 }]} >
                            <TouchableOpacity onPress={() => handleValidation()}>
                                <Text style={{ marginHorizontal: 40, marginVertical: 20, color: color.appWhiteColor, fontWeight: '600', fontSize: 16 }} >
                                    Next
                                </Text>
                            </TouchableOpacity>
                        </LinearGradient>
                    </View>
                </View>
            </View>
            <View style={{ marginBottom: 0 }} />
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
    flatView: {
        width: width / 2 - 30,
        borderRadius: 10, marginVertical: 10, borderWidth: 1, marginHorizontal: 2, height: 107, alignItems: 'center', marginLeft: 20,
        paddingVertical: 20
    }

});

export default AddPropertyOffer;