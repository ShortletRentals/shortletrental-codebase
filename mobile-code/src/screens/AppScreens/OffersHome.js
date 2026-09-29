import React, { useEffect, useState } from "react";
import { View, Image, Text, ImageBackground, StyleSheet, TextInput, TouchableOpacity, _Text, ScrollView, FlatList } from "react-native";
import { color, height, width } from "../../styles/colors";
import Header from "../../components/Header";
import { useFocusEffect, useRoute } from "@react-navigation/native";
import { CourtCardShimmer, MatchCardShimmer } from "../../components/Skeleton";
import { checkCouponCode, getOffer, getOfferDetailApiUse } from "../../network/Webconstant";
import AsyncStorage from "@react-native-async-storage/async-storage";
import axios from "axios";
import LinearGradient from "react-native-linear-gradient";
import ReactNativeModal from "react-native-modal";
// import FastImage from "react-native-fast-image";
import Images from "../../styles/Images";

let offerID = ''
const OffersHome = (props) => {
    const route = useRoute()
    const { price, propertyid, convenceFee, max_Gest
    } = route.params
    const [loader, setLoader] = useState(false)
    const [offerData, setOfferData] = useState([])
    const [offerDetails, setOfferDetails] = useState({})
    const [modal, setModal] = useState(false)

    useEffect(() => {
        getOfferApi()
    }, [props])
    const id = route.params.propertyid
    // console.log('==============================tok', props?.route.params.book, props.route.params.price, offerData, offerDetails)

   


    const getOfferDetailApi = async (item) => {
        setOfferDetails()
        // console.log('=====getoffer', item);
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
                    // console.log('====res get offer', response.data.status)

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


    const renderItemOffer = (item, index) => {
        // console.log('===item', item.item.id)
        return (
            <>
                {loader ? (
                    <MatchCardShimmer />
                ) : (
                    <View
                        activeOpacity={0.5}
                        // onPress={() =>
                        //   props.navigation.navigate('OfferDetail', {
                        //     offerID: item.item.id,
                        //     homeOffer: 'OFFER',
                        //   })
                        // }
                        style={{
                            // flex: 1,
                            // marginBottom: width * (1 / 375),
                            // borderColor: '#E9E9E9',
                            // borderWidth: 1,
                            // borderRadius: 80,
                            marginHorizontal: width * (20 / 375),
                            marginVertical: 10
                        }}>
                        <View style={{}}>
                            <ImageBackground
                                source={{ uri: item.item.image }}
                                style={{
                                    width: width * (335 / 375),
                                    height: width * (185 / 375),
                                    resizeMode: 'contain',
                                    borderRadius: 20,
                                    flex: 1,
                                    overflow: 'hidden',
                                }}>
                                <View
                                    style={{
                                        // backgroundColor: 'rgba(178, 176, 177, 0.49)',
                                        flex: 1,
                                        justifyContent: 'flex-end',
                                    }}>
                                    <Text
                                        style={{
                                            color: '#fff',
                                            width: width / 2 - 30,
                                            fontSize: 18,
                                            marginHorizontal: 14,
                                        }}>
                                        #{item?.item?.code}
                                    </Text>
                                    <Text
                                        style={{
                                            color: '#fff',
                                            marginTop: 4,
                                            width: width * (160 / 375),
                                            marginHorizontal: 14,
                                        }}>
                                        {item.item.title}
                                    </Text>
                                    <Text numberOfLines={1}
                                        style={{
                                            width: width / 2 - 30,
                                            marginVertical: 5,
                                            color: '#fff',
                                            fontSize: 12,
                                            marginHorizontal: 14,
                                        }}>
                                        {item.item.description}
                                    </Text>
                                    <LinearGradient colors={["#F99428", "#F1592A"]} style={{ marginHorizontal: 14, width: 120, borderRadius: 5, paddingVertical: 8 }}>
                                        <TouchableOpacity onPress={() => { setModal(!modal), getOfferDetailApi(item) }}>
                                            <Text style={{ textAlign: 'center',color:color.white }}>View Details</Text>
                                        </TouchableOpacity>
                                    </LinearGradient>
                                    <View style={{ marginBottom: 10 }} />
                                </View>
                            </ImageBackground>
                        </View>
                    </View>
                )}
            </>
        );
    };
    // const renderItem = (item, index) => {
    //     console.log('===item', item.item.id)
    //     return (
    //         <>
    //             {
    //                 loader ? <MatchCardShimmer />
    //                     :
    //                     <TouchableOpacity
    //                      onPress={() =>
    //                          props.navigation.navigate('OfferDetail', {
    //                         offerID: (item.item.id), price: route?.params?.price, propertyid, convenceFee, max_Gest: max_Gest, bookType: route?.params?.book
    //                     })} 
    //                     style={{ flexDirection: "row", marginTop: width * (20 / 375), padding: width * (10 / 375), marginBottom: width * (1 / 375), borderColor: '#E9E9E9', borderWidth: 1, borderRadius: 12, marginHorizontal: width * (20 / 375), backgroundColor: '#fff' }}>
    //                         {/* <TouchableOpacity onPress={() => props.navigation.navigate('OfferDetail', { offerID: (item.item.id) })} style={{ flexDirection: "row", marginTop: width * (20 / 375), padding: width * (10 / 375), marginBottom: width * (1 / 375), borderColor: '#E9E9E9', borderWidth: 1, borderRadius: 12, marginHorizontal: width * (20 / 375), backgroundColor: '#fff' }}> */}
    //                         <Image source={{ uri: item.item.image }} style={{
    //                             width: width * (135 / 375),
    //                             height: width * (135 / 375),
    //                             resizeMode: 'cover',
    //                             borderRadius: 10,
    //                             marginRight: width * (16 / 375)
    //                         }}
    //                         />
    //                         <View>
    //                             <Text style={{ color: color.appOrangeColor, width: width / 2 - 30 }}>{item.item.title}</Text>
    //                             <Text style={{ width: width / 2 - 30, marginTop: 4, color: color.primaryColorBlack }}>{item.item.description}</Text>
    //                             {/* <Text style={{ marginTop: 4, width: width * (160 / 375), color: color.primaryColorBlack }}>{item.Description}</Text> */}
    //                             <Text style={{ color: color.appTextColor, marginTop: 4, width: width * (160 / 375) }}>{item.item.discount}</Text>

    //                             <View style={{ flexDirection: "row", justifyContent: "space-between", width: width * (160 / 375), marginTop: 40 }}>
    //                                 <TouchableOpacity onPress={() => props.navigation.navigate('OfferDetail', { propertyid: id })}>
    //                                     <Text style={{ color: color.appOrangeColor }}>View More</Text>
    //                                 </TouchableOpacity>
    //                                 <TouchableOpacity onPress={() => {
    //                                     offerID = item?.item?.id,
    //                                     getOfferDetailApi(item?.item?.id),
    //                                     couponCodeApplyNow()
    //                                 }
    //                                 }>
    //                                     <Text style={{ color: color.appOrangeColor }}>Apply</Text>
    //                                 </TouchableOpacity>
    //                             </View>
    //                         </View>

    //                     </TouchableOpacity>
    //             }
    //         </>
    //     )
    // }
    return (
        <View style={{ flex: 1, backgroundColor: '#fff' }}>
            <Header props={props} Heading={'Special Offers'} onPress={() => props.navigation.navigate('Home'
                // , {
                //     max_Gest: max_Gest, price: props?.route?.params?.price
                // }
            )} />
            <FlatList
                data={offerData}
                renderItem={renderItemOffer}
                keyExtractor={(item, index) => index.toString()}
            />
            <View style={{marginVertical:10}}/>
            <ReactNativeModal isVisible={modal} onDismiss={() => setModal(false)} onBackdropPress={() => setModal(false)}
                style={{ backgroundColor: '#fff', flex: 0.7, marginTop: 150, borderRadius: 20 }}>
                <View style={{ marginHorizontal: 20 }}>
                    <TouchableOpacity onPress={() => setModal(false)}>
                        <Image source={Images.cancelImageIcon} style={{ height: 30, width: 30, borderRadius: 8, alignSelf: 'flex-end' }} resizeMode='contain' />
                    </TouchableOpacity>
                    <Image source={{ uri: offerDetails?.image }} style={{ height: 200, width: '100%', borderRadius: 10,marginVertical:5 }} resizeMode='cover' />
                    <Text style={{ color: '#000', fontSize: 16, fontWeight: '600' }}>#{offerDetails?.code}</Text>
                    <Text style={{ color: '#000' }}>{offerDetails?.title}</Text>
                    <Text style={{ color: '#000', fontSize: 12 }}>{offerDetails?.description}</Text>
                </View>
            </ReactNativeModal>
        </View>
    )
}
export default OffersHome;