import React, { useState } from "react";
import { View, Image, Text, ImageBackground, TouchableOpacity, TextInput, ScrollView, FlatList } from "react-native";
import { color, height, width } from "../../styles/colors";
import Images from "../../styles/Images";
import { commonStyles } from "./../../styles/style";
import { Colors, colors } from "react-native/Libraries/NewAppScreen";
import Header from "../../components/Header";
import TextView from "../../components/TextView";
import { useEffect } from "react";
import { useContext } from "react";
import { Context as AuthContext } from "../../context/AuthContext";
import { Context as ProfileContext } from "../../context/ProfileContext";
import moment from "moment";
import { Royalty_Points } from "../../network/Webconstant";
import { SliderShimmer } from "../../components/Skeleton";
import axios from "axios";
import AsyncStorage from "@react-native-async-storage/async-storage";
import { BackHandler } from "react-native";
import { useFocusEffect } from "@react-navigation/native";



const LoyaltyPoints = (props) => {


    const [royaltyPoint, setRoyaltyPoint] = useState([])
    const [loader, setLoader] = useState(false)
    const handler = ()=>{
        props.navigation.navigate('Account')
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

    const getRoyaltyApi = async () => {
        const Localtoken = await AsyncStorage.getItem('token_id');
        setLoader(true)
        try {
            await axios({
                method: 'get',
                url: Royalty_Points,
                headers: { Authorization: `Bearer ${Localtoken}` }
            }).then(
                function (res) {
                    // console.log('====res', res.data.data)
                    if (res.data.status) {
                        setLoader(false)
                        setRoyaltyPoint(res.data.data)

                    } else {
                        setLoader(false)
                    }
                }
            )
        } catch (error) {
            console.log('error is ', error)
            setLoader(false)
        }
    }

    useEffect(() => {

        getRoyaltyApi()
    }, [props])

    // console.log('===============royal', royaltyPoint.loyalty_points[0].title);
    return (
        <View style={commonStyles.container}>

            <Header props={props} Heading={'Loyalty Points'} onPress={() => props.navigation.navigate('Account')} />
            {
                loader ? <SliderShimmer /> :

                    // <ScrollView contentContainerStyle={{ flexGrow: 1 }}>
                    <View>
                        <View style={{ marginHorizontal: 20, marginTop: 10 }}>
                            <View style={{ borderRadius: 8, borderWidth: 1, borderColor: '#F99428', marginTop: 15, alignSelf: 'center', height: 80, width: '100%', backgroundColor: '#F7F6FC', marginBottom: 20 }}>
                                <View style={{}}>
                                    <Text style={{ fontSize: 18, marginTop: 10, color: color.primaryColorBlack, fontWeight: '600', paddingHorizontal: 20 }}>Points</Text>
                                    <Text style={{ fontSize: 26, color: color.appOrangeColor, fontWeight: 'bold', paddingHorizontal: 20 }}>{royaltyPoint?.total_loyalty_points} Points</Text>
                                </View>
                            </View>
                        </View>

                        <View style={{ borderWidth: 1, borderColor: '#E9E9E9', }} />
                        <View style={{ marginHorizontal: 20 }}>
                            <Text style={{ marginTop: 10, color: color.primaryColorBlack, fontSize: 14, fontWeight: '500' }}>TRANSACTIONS</Text>
                            <View style={{}}>
                                {royaltyPoint?.loyalty_points == '' ? <Text style={{ fontSize: 16, color: '#000', marginTop: 10 }}>No Data Found</Text>
                                    :
                                    <FlatList
                                        data={royaltyPoint.loyalty_points}
                                        renderItem={(item, index) => {
                                            // console.log('item is ', item);
                                            return (
                                                <View style={{ marginVertical: 6, flexDirection: 'row', justifyContent: 'space-between', borderBottomWidth: 1, borderBottomColor: '#e9e9e9' }}>
                                                    {
                                                        item.type == 'Debit' ? <Image source={Images.RecievedMoney} style={{ height: 30, width: 30, marginVertical: 5 }} /> : <Image source={Images.SendMoney} style={{ height: 30, width: 30, marginVertical: 5 }} />
                                                    }
                                                    <View style={{ flexDirection: 'row', justifyContent: 'flex-start', marginHorizontal: 10 }}>
                                                        <View style={{}}>
                                                            <Text style={{ color: color.primaryColorBlack, width: width / 2, fontSize: 14 }}>{item?.item?.points}</Text>
                                                            <Text style={{ marginVertical: 4, fontSize: 16, width: width / 2,color:'#000' }}>{item?.item?.title}</Text>
                                                        </View>

                                                    </View>
                                                    <View style={{ width: width / 2 - 100 }}>
                                                        <Text style={{color:'#000'}}>
                                                            {moment(item.created_at).format('DD')} th {moment(item.created_at).format('ddd')}
                                                        </Text>
                                                    </View>
                                                </View>
                                            )
                                        }}
                                    />
                                }



                            </View>
                        </View>
                    </View>
            }
        </View>


    )
}

export default LoyaltyPoints;