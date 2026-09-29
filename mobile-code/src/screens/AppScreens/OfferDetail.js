import React, { useState } from "react";
import { View, Image, Text, ImageBackground, TouchableOpacity, InteractionManager, _Text, ScrollView, ActivityIndicator, BackHandler } from "react-native";
import { color, height, width } from "../../styles/colors";
import Images from "../../styles/Images";
import { commonStyles } from "./../../styles/style";
import { Colors, colors } from "react-native/Libraries/NewAppScreen";
import Header from "../../components/Header";
import TextView from "../../components/TextView";
import { useFocusEffect, useIsFocused, useNavigation, useRoute } from '@react-navigation/native';
import { useEffect } from "react";
import { useContext } from "react";
import { Context as BookingContext } from "../../context/BookingContext";
import { Context as AuthContext } from "../../context/AuthContext";
import { SliderShimmerHomeDetail, SliderShimmerOfferDetail } from "../../components/Skeleton";
import { checkCouponCode, getOfferDetailApiUse } from "../../network/Webconstant";
import axios from "axios";
import AsyncStorage from "@react-native-async-storage/async-storage";
import { Toast } from "react-native-toast-message/lib/src/Toast";


const OfferDetail = (props) => {
    const [loader, setLoader] = useState(false)
    const [offerDetails, setOfferDetails] = useState({})
    const route = useRoute()
    const { offerID } = props?.route?.params
    const navigation = useNavigation()
    // console.log('====id', route.params.offerID, route.params.price, props.route.params.propertyid, route.params.bookType)
    const { state: { Token_ID } } = useContext(AuthContext)
    // const { getOfferDetailApi, getCouponCodeApi, state: { offerDetails, activityIndicator, applyCoupon } } = useContext(BookingContext)
    // console.log('===offferdE', route.params.bookType);

    // alert(offerID)

    const { price, propertyid, convenceFee, max_Gest,loyalty_points,points_amount } = route.params


    useFocusEffect(
        React.useCallback(() => {

            getOfferDetailApi()

        }, [offerID])
    );

    const handler = () => {
        route?.params?.homeOffer == 'OFFER' ? props?.navigation?.navigate('Home') : props.navigation.navigate('Offers', { id: route.params.offerID, price: route?.params?.price, propertyid: props.route.params.propertyid, bookType: route.params.bookType,loyalty_points:loyalty_points,points_amount:points_amount })
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
        }, [props])
    )
    const getOfferDetailApi = async () => {
        const Localtoken = await AsyncStorage.getItem('token_id');
        setLoader(true)
        try {
            axios({
                method: 'post',
                url: getOfferDetailApiUse,
                data: { offer_id: offerID },
                headers: { "Accept": "application/json", "Authorization": `Bearer ${Localtoken}` }
            }).then(
                function (res) {
                    // console.log('=====res', res.data.data);
                    if (res.data.status == true) {
                        setLoader(false)
                        setOfferDetails(res.data.data)
                    } else {
                        // alert(res.data.message)
                        setLoader(false)
                    }
                }
            )
        } catch (error) {
            console.log('e', error)
            setLoader(false)
        }
    }
    // console.log('===offerDetails.code', route.params.homeOffer)
    //  onPress={()=>props.navigation.navigate('Offers')}
    const couponCodeApplyNow = async () => {


        const Localtoken = await AsyncStorage.getItem('token_id');
        let data = { coupon_code: (offerDetails.code) }
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
                    // console.log(res.data, "==========data====>>>");
                    // console.log('=====resCheck coupan data', res.data);
                    if (res.data.status == true) {
                        // alert(res.data.message)
                        navigation.navigate('Booking', {
                            id: route.params.offerID, myId: res.data?.data?.percentage, price: route?.params?.price, propertyid, convenceFee, code: res.data.data.code, max_Gest: max_Gest,
                            // bookType: 'Reserve' 
                            bookType: route?.params?.bookType,
                            loyalty_points:loyalty_points,
                            points_amount:points_amount
                        })
                    } else {
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
    return (
        <View style={[commonStyles.container, { color: color.appWhiteColor }]}>
            <Header props={props} Heading={'Offer Detail'} onPress={() => route?.params?.homeOffer == 'OFFER' ? props?.navigation?.navigate('Home') : props.navigation.navigate('Offers', { id: route.params.offerID, price: route?.params?.price, propertyid: props.route.params.propertyid, bookType: route.params.bookType,loyalty_points:loyalty_points,points_amount:points_amount })} />
            {loader ? <SliderShimmerOfferDetail /> :
                <ScrollView style={{ marginHorizontal: 20 }}
                    showsVerticalScrollIndicator={false}
                >
                    <View style={{ height: 200, backgroundColor: '#fff', marginTop: 20, borderRadius: 10, width: '100%' }}>
                        <Image source={{ uri: offerDetails.image }} resizeMode='contain'
                            style={{ height: 200, alignSelf: 'center', borderRadius: 10, width: '100%' }} />
                    </View>
                    <View style={{ marginTop: 14 }}>
                        <ImageBackground resizeMode="contain" source={Images.DottedRectangleIcon} style={{ height: 60, width: '100%', alignItems: 'center', justifyContent: 'center' }}>
                            <Text style={{ color: color.appOrangeColor, fontSize: 20, fontWeight: '600' }}>{offerDetails?.code}</Text>
                        </ImageBackground>
                    </View>
                    <View style={{ marginBottom: 20 }}>
                        <Text style={{ fontSize: 14, marginTop: 10, color: color.primaryColorBlack }}>{offerDetails?.title}</Text>
                        <Text style={{ fontSize: 16, fontWeight: '600', marginTop: 6, color: color.primaryColorBlack }}>{offerDetails.description}</Text>
                        <Text style={{ marginTop: 6, fontSize: 13, color: '#000' }}>treat yourself to a refreshing.</Text>
                        <Text style={{ marginTop: 10, fontSize: 13, color: '#000' }}>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</Text>
                        <Text style={{ marginTop: 10, fontSize: 13, color: '#000' }}>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</Text>
                        {
                            route?.params?.homeOffer == 'OFFER' ? null :

                                <TouchableOpacity
                                    //  onPress={()=>props.navigation.navigate('Offers')}
                                    onPress={() => couponCodeApplyNow()}
                                    style={{
                                        marginTop: 20,
                                        backgroundColor: color.appOrangeColor,
                                        width: '100%',
                                        padding: 10,
                                        borderRadius: 10,
                                        justifyContent: 'center',
                                        alignSelf: 'center',
                                        marginBottom: 80,
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
                                    <Text style={{ textAlign: "center", fontSize: 15, color: color.appWhiteColor }}>Apply Now</Text>
                                </TouchableOpacity>
                        }
                    </View>
                </ScrollView>
            }
        </View>
    )
}
export default OfferDetail;

