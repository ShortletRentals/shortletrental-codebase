import React, { useEffect, useState } from "react";
import { View, Image, Text, ImageBackground, StyleSheet, TextInput, TouchableOpacity, _Text, ScrollView, FlatList } from "react-native";
import { color, height, width } from "../../styles/colors";
import Header from "../../components/Header";
import { useFocusEffect, useRoute } from "@react-navigation/native";
import { CourtCardShimmer, MatchCardShimmer } from "../../components/Skeleton";
import { checkCouponCode, getOffer, getOfferDetailApiUse } from "../../network/Webconstant";
import AsyncStorage from "@react-native-async-storage/async-storage";
import axios from "axios";
import { BackHandler } from "react-native";
import { Toast } from "react-native-toast-message/lib/src/Toast";

let offerID = ''
const Offers = (props) => {
    const route = useRoute()
    const { price, propertyid, convenceFee, max_Gest,loyalty_points,points_amount
    } = route.params
    const [loader, setLoader] = useState(false)
    const [offerData, setOfferData] = useState([])
    const [offerDetails, setOfferDetails] = useState({})

    useEffect(() => {
        getOfferApi()
    }, [props])
    const id = route.params.propertyid
    // console.log('==============================tok', offerDetails, props?.route.params.bookType, props.route.params.price, props.route.params.propertyid, propertyid)

    useFocusEffect(
        React.useCallback(() => {
            getOfferDetailApi()
        }, [props])
    );
    const handler = () => {
        props.navigation.navigate('Booking', {
            max_Gest: max_Gest, price: props?.route?.params?.price, propertyid: props.route.params.propertyid, bookType: props?.route.params.bookType,loyalty_points:loyalty_points,points_amount:points_amount
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

    const getOfferDetailApi = async (item) => {
        console.log('=====getoffer', item);
        const Localtoken = await AsyncStorage.getItem('token_id');
        // setLoader(true)
        try {
            axios({
                method: 'post',
                url: getOfferDetailApiUse,
                data: { offer_id: item },
                headers: { "Accept": "application/json", "Authorization": `Bearer ${Localtoken}` }
            }).then(
                function (res) {
                    // console.log('=====res', res.data.data);
                    if (res.data.status == true) {
                        // setLoader(false)
                        setOfferDetails(res.data.data)
                        console.log(res.data.data)
                        couponCodeApplyNow(res.data.data.code)
                    } else {
                        // alert(res.data.message)
                        // setLoader(false)
                    }
                }
            )
        } catch (error) {
            console.log('e', error)
            // setLoader(false)
        }
    }
    const getOfferApi = async () => {
        const Localtoken = await AsyncStorage.getItem('token_id');
        setLoader(true)
        try {
            axios({
                method: 'get',
                url: getOffer,
                headers: { "Accept": "application/json", "Authorization": `Bearer ${Localtoken}` }
            }).then(
                function (response) {
                    console.log('====res get offer', response.data.status)

                    if (response.data.status === true) {
                        setLoader(false)
                        setOfferData(response.data.data)

                    }
                }
            )
        } catch (error) {
            console.log('eerrr', error)
            setLoader(false)
        }
    }

    const couponCodeApplyNow = async (code) => {
        const Localtoken = await AsyncStorage.getItem('token_id');
        let data = { coupon_code:code }
        let headers = {
            Authorization: `Bearer ${Localtoken}`
        }
        try {
            axios({
                method: 'post',
                url: checkCouponCode,
                data,
                headers
            }).then(
                function (res) {
                    console.log('=====resCheck coupan data', res.data);
                    if (res.data.status == true) {
                        props?.navigation?.navigate('Booking', {
                            id: offerID, myId: res.data?.data?.percentage, price: props?.route?.params?.price, propertyid, convenceFee, code: res.data.data.code, max_Gest: max_Gest,
                            bookType: props?.route?.params?.bookType,loyalty_points:loyalty_points,points_amount:points_amount
                        })

                    } else {
                        console.log('errorr', res.data.message);
                        Toast.show({
                            type: 'error',
                            text1: 'Shortlet',
                            text2: res.data.message
                        })
                    }
                }
            )
        } catch (error) {
            console.log('e', error)
        }

    }

    const renderItem = (item, index) => {
        console.log('===item', item.item.id)
        return (
            <>
                {
                    loader ? <MatchCardShimmer />
                        :
                        <TouchableOpacity onPress={() => props.navigation.navigate('OfferDetail', {
                            offerID: (item.item.id), price: route?.params?.price, propertyid, convenceFee, max_Gest: max_Gest,
                             bookType: route?.params?.bookType,
                             loyalty_points:loyalty_points,
                             points_amount:points_amount
                        })} style={{ flexDirection: "row", marginTop: width * (20 / 375), padding: width * (10 / 375), marginBottom: width * (1 / 375), borderColor: '#E9E9E9', borderWidth: 1, borderRadius: 12, marginHorizontal: width * (20 / 375), backgroundColor: '#fff' }}>
                            {/* <TouchableOpacity onPress={() => props.navigation.navigate('OfferDetail', { offerID: (item.item.id) })} style={{ flexDirection: "row", marginTop: width * (20 / 375), padding: width * (10 / 375), marginBottom: width * (1 / 375), borderColor: '#E9E9E9', borderWidth: 1, borderRadius: 12, marginHorizontal: width * (20 / 375), backgroundColor: '#fff' }}> */}
                            <Image source={{ uri: item.item.image }} style={{
                                width: width * (135 / 375),
                                height: width * (135 / 375),
                                resizeMode: 'cover',
                                borderRadius: 10,
                                marginRight: width * (16 / 375)
                            }}
                            />
                            <View>
                                <Text style={{ color: color.appOrangeColor, width: width / 2 - 30 }}>{item.item.title}</Text>
                                <Text style={{ width: width / 2 - 30, marginTop: 4, color: color.primaryColorBlack }}>{item.item.description}</Text>
                                {/* <Text style={{ marginTop: 4, width: width * (160 / 375), color: color.primaryColorBlack }}>{item.Description}</Text> */}
                                <Text style={{ color: color.appTextColor, marginTop: 4, width: width * (160 / 375) }}>{item.item.discount}</Text>

                                <View style={{ flexDirection: "row", justifyContent: "space-between", width: width * (160 / 375), marginTop: 40 }}>
                                    <TouchableOpacity 
                                    // onPress={() => props.navigation.navigate('OfferDetail', { propertyid: id })}
                                    >
                                        <Text style={{ color: color.appOrangeColor }}>View More</Text>
                                    </TouchableOpacity>
                                    <TouchableOpacity onPress={() => {
                                        offerID = item?.item?.id,
                                            getOfferDetailApi(item?.item?.id)
                                            
                                    }
                                    }>
                                        <Text style={{ color: color.appOrangeColor }}>Apply</Text>
                                    </TouchableOpacity>
                                </View>
                            </View>

                        </TouchableOpacity>
                }
            </>
        )
    }
    return (
        <View style={{ flex: 1, backgroundColor: '#fff' }}>
            <Header props={props} Heading={'Offers'} onPress={() => props.navigation.navigate('Booking', {
                max_Gest: max_Gest, price: props?.route?.params?.price, propertyid: props.route.params.propertyid, bookType: props?.route.params.bookType,points_amount:points_amount,loyalty_points:loyalty_points
            })} />
            <FlatList
                data={offerData}
                renderItem={renderItem}
                keyExtractor={(item, index) => index.toString()}
            />
        </View>
    )
}
export default Offers;