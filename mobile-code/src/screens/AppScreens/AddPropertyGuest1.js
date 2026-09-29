import React, { useState, useEffect } from "react";
import {
    View, Image, Text, ImageBackground, StyleSheet, TextInput, TouchableOpacity,
    ActivityIndicator, _Text, ScrollView
} from "react-native";
import { color, height, width } from "../../styles/colors";
import Images from "../../styles/Images";
import { commonStyles } from "./../../styles/style";
import { Colors, colors } from "react-native/Libraries/NewAppScreen";
import Header from "../../components/Header";
import { SafeAreaView } from "react-native-safe-area-context";
import LinearGradient from "react-native-linear-gradient";
import { Toast } from "react-native-toast-message/lib/src/Toast";
import { getAllProperty } from "../../network/Webconstant";
import { useIsFocused, useNavigation, useRoute } from "@react-navigation/native";
import {


    Circle,
    Svg,
    SvgUri,
} from 'react-native-svg';

import axios from "axios";
import { BackHandler } from "react-native";






let categoryId = ""



const renderData = [
    { image: Images.House1, title: 'National Park' },
    { image: Images.House2, title: 'Tiny Homes' },
    { image: Images.House3, title: 'Island' },
    { image: Images.House4, title: 'Amazing Pool' },
    { image: Images.House5, title: 'Cabin' },
    { image: Images.House6, title: 'Home' }


]

const AddPropertyguest1 = (props) => {
    const route = useRoute()
    const navigation = useNavigation()
    const [isSelect, setIsSelect] = useState(null)

    const [selectedClients, setSelectedClients] = useState([])
    const [propData, setPropData] = useState([])
    const [userId, setUserId] = useState(route.params.userId)
    const [propertyId, setPropertyId] = useState(route.params.propertyId)
    const [loader, setLoader] = useState(false);
    const [multiData, setMultiData] = useState([])
    const [selectedArr, setSelectedArr] = useState([])


    useEffect(() => {
       
        setLoader(true)
        try {
            axios({
                method: "post",
                url: getAllProperty,
                data: { type: route.params.propertyId }
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

        } catch (error) {
            console.log('error is add property guest1', error);
            setLoader(false)
        }


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
                text2: "Please select atleast one category",


            })
        } else {
            props.navigation.navigate('AddPropertyLocated', {
                propertyId: propertyId, userId: userId,
                //  categoryId: selectedArr
                categoryId: selectedArr
            })

        }
    }


    const selectAction = (item, status) => {
        let arr = [...selectedArr]
        if (status) {
            const index = arr.indexOf(item?.id)
            if (index > -1) { // only splice array when item is found
                arr.splice(index, 1); // 2nd parameter means remove one item only
            }
        } else {
            arr.push(item?.id)
        }
        setSelectedArr(arr)
    }

    // data.item.isSelect = !data.item.isSelect;
    // data.item.selectedClass = data.item.isSelect ? styles.selected : styles.list;
    // console.log('========isselct', isSelect);
    return (
        <View style={commonStyles.container}>

            <Header props={props} Heading={'Add Property'} onPress={() => props.navigation.goBack()} />
            {
                loader ? (
                    <View style={{ justifyContent: 'center', marginVertical: "40%", }}>
                        <ActivityIndicator size="large" color="#F99428" />
                    </View >
                ) :
                    <ScrollView showsVerticalScrollIndicator={false} style={{ flex: 1 }}>
                        <Text style={{ marginHorizontal: 20, marginTop: 20, fontSize: 17, fontWeight: '500', color: color.primaryColorBlack }}>
                            {/* What kind of place will you host ? */}
                            Select all that applies
                            </Text>
                        {/* {
                            propData = [] ?<View> <Text>No Data Fount</Text></View> : null
                        } */}
                        {
                            propData == '' ? <Text style={{ alignSelf: 'center' }}>No Data Found please select other category</Text>
                                :
                                propData.map((item, index) => {
                                    console.log('=-=-=-=-=item',item.image.match('https://shortletrental.com') == item.image);
                                    const status = selectedArr.includes(item?.id)
                                    return (
                                        <TouchableOpacity onPress={() => selectAction(item, status)} style={[styles.flatView, { borderColor: status ? color.appYellowColor : color.appTextColor }]}>
                                            <Text style={{ marginHorizontal: 10, color: color.primaryColorBlack, fontWeight: '500' }}>{item.name}</Text>
                                            {
                                                item.image.match('svg') == 'svg' ?

                                                    <Svg style={{ height: 50, width: 50, resizeMode: "cover", borderTopRightRadius: 5, borderBottomRightRadius: 5,backgroundColor:color.appOrangeColor,margin:2}} >
                                                        <SvgUri
                                                            width="100%"
                                                            height="100%"
                                                            uri={item?.image}
                                                        // uri="https://zea-virtual-events.com/shortletrental/uploads/category/DEC2022/1670504332-category.svg"
                                                        // fill='blue'
                                                        // stroke={'#F99428' }
                                                        />
                                                    </Svg>
                                                    :
                                                    <View>

                                                        <Image source={{ uri: item.image }} style={{ height: 50, width: 50 }} />
                                                    </View>
                                            }

                                        </TouchableOpacity>


                                    )
                                })}
                      
                        <View style={{ flex: 1, justifyContent: 'flex-end' }}>
                            <View style={{ padding: 20, backgroundColor: color.appTextBackgoundColor, flexDirection: 'row', justifyContent: 'space-between', marginTop: 20 }}>
                                <TouchableOpacity onPress={() => props.navigation.goBack()} style={{ backgroundColor: color.appBlueColor, borderRadius: 10 }}><Text style={{ fontSize: 18, color: color.appWhiteColor, marginHorizontal: 40, marginVertical: 20, fontWeight: '600' }}>Back</Text></TouchableOpacity>
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
            }

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
    flatView: { marginHorizontal: 20, marginTop: 10, flexDirection: 'row', justifyContent: 'space-between', borderRadius: 8, borderWidth: 1, alignItems: 'center' }

});


export default AddPropertyguest1;