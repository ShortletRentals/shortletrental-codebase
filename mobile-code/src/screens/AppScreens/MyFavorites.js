import React, { useState, useContext, useEffect } from "react";
import { View, Image, Text, TouchableOpacity, FlatList, SafeAreaView, StatusBar, InteractionManager } from "react-native";
import { ScrollView } from "react-native-gesture-handler";
import { color, height, width } from "../../styles/colors";
import Images from "../../styles/Images";
import { SliderBox } from "react-native-image-slider-box";
import axios from "axios";
import { my_favorites } from "../../network/Webconstant";
import { Context as HomeContext } from "../../context/HomeContext";
import { Context as AuthContext } from "../../context/AuthContext";
import { SliderShimmer } from "../../components/Skeleton";
import LinearGradient from "react-native-linear-gradient";
import { useFocusEffect } from "@react-navigation/native";
import CustomImageFlatList from "../../components/CustomImageFlatList";
import Header from "../../components/Header";
import { id } from "deprecated-react-native-prop-types/DeprecatedTextPropTypes";
import AsyncStorage from "@react-native-async-storage/async-storage";
import { ActivityIndicator } from "react-native";
import { BackHandler } from "react-native";
import { Alert } from "react-native";




let newData = []
// const renderData = [
//     { image: Images.calenderIcon, title: 'Modest 1-bedroom apartment', Hotelname: 'Lekki - Apartment', Description: 'NGN 200.00', Name: ' night' },
//     { image: Images.House, title: 'Modular 2-bedroom apartment', Hotelname: 'Lekki -full Flat', Description: 'NGN 250.00', Name: ' day' },
//     { image: Images.fbIcon, title: 'full furnished bedroom flat', Hotelname: 'fully furnished dta', Description: 'NGN 300.00', Name: ' night' },
//     { image: Images.fbIcon, title: 'semi furnisgedd', Hotelname: 'semi furnished', Description: 'NGN 350.00', Name: ' night' }
// ]

const Myfavorites = (props) => {

    const [data, setData] = useState([])

    const [loader, setLoader] = useState(true)
    const { getToFavourite, addToFavourite, } = useContext(HomeContext)

    useFocusEffect(
        React.useCallback(() => {
            myfavoritesApi()
        }, [props]),
    );

    const handler = () => {
        props.navigation.navigate('Home')
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

    const myfavoritesApi = async () => {
        // console.log('------------------data----------', data.length);
        const Localtoken = await AsyncStorage.getItem('token_id');
        setLoader(true)
        await axios({
            method: 'get',
            url: my_favorites,
            // data: { page: 1, limit: 'ALL' },
            headers: { "Authorization": `Bearer ${Localtoken}`, "Accept": "application/json" }
        }).then(
            function (response) {
                // console.log('myFavourite api----------------response')
                if (response.data.status === true) {
                    setLoader(false)
                    setData(response.data.data)
                    // newData= response.data.data.get_property.is_fav
                    // console.log("my state data------------ ", data.length)

                } else {
                    setLoader(false)
                }
            }
        ).catch((e) => {
            console.log('something went wrong favourite', e);
            // controller.abort()
            setLoader(false)
        })
    }
    const myfavoritesApiNew = async () => {
        const Localtoken = await AsyncStorage.getItem('token_id');
        // setLoader(true)
        axios({
            method: 'get',
            url: my_favorites,
            // data: { page: 1, limit: 'ALL' },
            headers: { "Authorization": `Bearer ${Localtoken}`, "Accept": "application/json" }
        }).then(
            function (response) {
                // console.log('myFavourite fghrthrthtr api', response.data.data.length)
                if (response.data.status === true) {
                    setData(response.data.data)
                    // console.log('myFavourite after response api', response.data.data.length)

                    // newData =data
                    // setData([...data].filter(a=>a.id !== id))
                    // setLoader(false)
                } else {
                    // setLoader(false)
                }
            }
        ).catch((e) => {
            console.log('something went wrong favourite', e);
            // controller.abort()
            setLoader(false)
        })
    }

    // console.log('==========',data);
    const addToFavouriteApi = async (item) => {
        // tokenDevice()
        const Localtoken = await AsyncStorage.getItem('token_id');
        const USER_ID = await AsyncStorage.getItem('USER_ID');
        let data = { userId: USER_ID, product_id: item }
        let headers = { Authorization: `Bearer ${Localtoken}` }

        addToFavourite(data, headers, () => {
            myfavoritesApiNew()
        })

    }




    const renderItem = ({ item, index }) => {
        // console.log('-------item.g',item.get_property);
        return (
            <>
                <View
                >
                    <View
                        style={{
                            marginTop: width * (21 / 375),
                            //   paddingHorizontal: width * (10 / 375),
                            // marginBottom: width * (4 / 375), 
                            borderColor: '#E9E9E9',
                            borderWidth: 1,
                            borderRadius: 12,
                            marginHorizontal: width * (20 / 375),
                            backgroundColor: '#FFFFFF',
                            width: width * (334 / 375),
                            borderTopRightRadius: 10,
                            borderTopLeftRadius: 10,
                            borderBottomLeftRadius: 10,
                            borderBottomRightRadius: 10,
                        }}
                    >
                        <CustomImageFlatList
                            data={item?.get_property.get_property_images.length > 1 ? item?.get_property?.get_property_images?.map(img => img.image) :
                                ["https://images.unsplash.com/photo-1670436435847-160ac8dd8d31?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxlZGl0b3JpYWwtZmVlZHw0fHx8ZW58MHx8fHw%3D&auto=format&fit=crop&w=400&q=60",
                                    "https://images.unsplash.com/photo-1670436435847-160ac8dd8d31?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxlZGl0b3JpYWwtZmVlZHw0fHx8ZW58MHx8fHw%3D&auto=format&fit=crop&w=400&q=60"]}
                        />


                        <TouchableOpacity onPress={() => props?.navigation?.navigate('HomeDetails', { propertyid: item?.id, MY_FAV: 'MY_FAV' })}>
                            <View style={{ marginHorizontal: 15, marginTop: 8 }}>
                                <Text numberOfLines={1} style={{ fontSize: 19, fontWeight: '500', color: color.primaryColorBlack, }}>{item.get_property.title}</Text>
                                <View style={{ flexDirection: "row", justifyContent: 'space-between', width: width * (300 / 375) }}>
                                    <View style={{ flexDirection: "row", width: width * (150 / 375), marginTop: 4 }}>
                                        <TouchableOpacity>
                                            <Image source={Images.LocationIcons} style={{
                                                paddingLeft: 10,
                                                width: 20, height: 20, resizeMode: 'contain',
                                            }} />
                                        </TouchableOpacity>
                                        <TouchableOpacity>
                                            <Text style={{ paddingLeft: 10, color: '#000' }}>{item.get_property.type}</Text>
                                        </TouchableOpacity>
                                    </View>
                                    <View style={{ flexDirection: "row", width: width * (70 / 375), marginTop: 4, justifyContent: 'flex-end', alignItems: 'center', marginHorizontal: 0 }}>
                                        <TouchableOpacity>
                                            <Image source={Images.StartIcon} style={{
                                                paddingLeft: 10,
                                                width: 16, height: 16, resizeMode: 'contain',
                                            }} />
                                        </TouchableOpacity>
                                        <Text style={{ color: color.primaryColorBlack, marginHorizontal: 4 }}>{item.get_property.avg_rating == null ? 0.0 : item.get_property.avg_rating}({item.get_property.total_rating == null ? 0 : item.get_property.total_rating}) </Text>

                                    </View>
                                </View>
                                <View style={{ flexDirection: 'row', alignItems: 'center' }}>
                                    <Text style={{ marginTop: 4, fontWeight: '500', color: color.primaryColorBlack, fontSize: 18 }}>NGN {item.get_property.price}.00</Text>
                                    {/* <Text style={{ marginTop: 4 }}></Text> */}
                                    <Text style={{ marginTop: 4, fontSize: 15, color: '#000' }}> Night</Text>

                                </View>

                                <View style={{ flexDirection: 'row', justifyContent: 'space-between', marginBottom: 10 }}>
                                    <View style={{ flexDirection: "row", width: width * (130 / 375), height: 40, marginTop: 4, justifyContent: 'space-between' }}>
                                        <View style={{ flexDirection: "row", width: width * (60 / 375), marginTop: 4, borderRadius: width * (8 / 375), borderWidth: 1, borderColor: color.appTextColor, justifyContent: 'center', alignItems: 'center' }}>
                                            <View>
                                                <Image source={Images.GroupIcons} style={{
                                                    paddingLeft: 10,
                                                    width: 20, height: 20, resizeMode: 'contain',
                                                }} />
                                            </View>
                                            <View>
                                                <Text style={{ paddingLeft: 10, color: '#000' }}>{item.get_property.max_guest}</Text>
                                            </View>
                                        </View>
                                        <View style={{ flexDirection: "row", width: width * (60 / 375), marginTop: 4, borderRadius: width * (8 / 375), borderWidth: 1, borderColor: color.appTextColor, justifyContent: 'center', alignItems: 'center' }}>
                                            <View>
                                                <Image source={Images.BedIcons} style={{
                                                    paddingLeft: 10,
                                                    width: 20, height: 20, resizeMode: 'contain',
                                                }} />
                                            </View>
                                            <View>
                                                <Text style={{ paddingLeft: 10, color: '#000' }}>{item.get_property.get_property_bedroom[0].no_of_bedrooms}</Text>
                                            </View>

                                        </View>

                                    </View>
                                    <View style={{ backgroundColor: color.appBlueColor, height: 34, width: 34, borderRadius: 20, alignItems: 'center', justifyContent: 'center' }}>
                                        <Image source={Images.SeekLogoIcons} style={{

                                            width: 20, height: 20, resizeMode: 'contain', justifyContent: 'center', alignContent: 'center'
                                        }} />
                                    </View>
                                </View>

                            </View>
                        </TouchableOpacity>
                    </View>
                    <TouchableOpacity
                        onPress={() => addToFavouriteApi(item.id)}
                        style={{ alignSelf: 'flex-end', marginHorizontal: 10, zIndex: 1000, position: 'absolute', right: 20, marginTop: 30 }} >
                        <Image source={item.is_fav === 0 ? Images.heartIcon : Images.heart} style={{ height: 24, width: 24 }} resizeMode='contain' />
                    </TouchableOpacity>
                </View>
            </>
        )
    }
    return (
        <View style={{ flex: 1 }}>
            <Header props={props} Heading={'My Favorite'} onPress={() => props?.navigation?.navigate('Home')} />
            <StatusBar backgroundColor={'transparent'} translucent />
            {
                loader ?
                    <SliderShimmer />
                    // <ActivityIndicator size={'large'} color={'#000'}/>
                    :
                    <View style={{ marginTop: 0,flex:1 }}>
                        {
                            data.length == 0 ?
                                <View style={{ justifyContent: 'center', alignItems: 'center', flex:1 }}>
                                    <Image source={Images.No_Data} style={{ height: 200, width: 200 }} />

                                    <Text style={{ alignSelf: 'center', color: '#000', fontSize: 20, justifyContent: 'center', }}>No Data Found</Text></View> :


                                < View >
                                    <FlatList
                                        data={data}
                                        renderItem={renderItem}
                                        keyExtractor={(item, index) => index.toString()}
                                        ListFooterComponent={() => <View style={{ backgroundColor: 'red', marginBottom: 100 }} />}
                                        showsVerticalScrollIndicator={false}
                                    />
                                </View>
                        }
                    </View>
            }
            {/* <View style={{ marginBottom:1000 }} /> */}
        </View >
    )
}

export default Myfavorites;

