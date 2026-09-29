import { ActivityIndicator, BackHandler, FlatList, StyleSheet, Text, View } from 'react-native'
import React, { useEffect, useState } from 'react'
import { TouchableOpacity } from 'react-native'
import { Image } from 'react-native'
import Images from '../../styles/Images'
import { color } from '../../styles/colors'
import { useFocusEffect, useNavigation } from '@react-navigation/native'
import axios from 'axios'
import { getReserveList } from '../../network/Webconstant'
import AsyncStorage from '@react-native-async-storage/async-storage'
import { Toast } from 'react-native-toast-message/lib/src/Toast'
import { height } from 'deprecated-react-native-prop-types/DeprecatedImagePropType'
import Header from '../../components/Header'
import { width } from '../../styles/style'
import moment from 'moment'

const MyReservation = (props) => {
    const [reserveList, setReserveList] = useState([])
    const [loader, setLoader] = useState(false)


    const handler = () => {
        props.navigation.navigate('Account')
    }
    useFocusEffect(
        React.useCallback(() => {
            getBookingReservationApi()
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


    const getBookingReservationApi = async () => {
        const Localtoken = await AsyncStorage.getItem('token_id');
        const USER_ID = await AsyncStorage.getItem('USER_ID');
        try {
            setLoader(true)
            axios({
                method: 'get',
                url: getReserveList,
                // data: { user_id: USER_ID },
                headers: {
                    Authorization: `Bearer ${Localtoken}`,
                    Accept: 'application/json'
                },
            }).then((res) => {
                console.log(res.data, 'ressljkjalksjfljslkjfkldsjfget')
                if (res.data.status) {
                    setReserveList(res.data.data)
                    setLoader(false)

                } else {
                    Toast.show({ type: 'error', text1: res.data.message })
                    setLoader(false)
                    setReserveList([])

                }
            })
        } catch (error) {
            console.log(error);
            setLoader(false)

        }
    }

    const renderItem = ({ item, index }) => {
        console.log('---tiem', item)
        return (
            <View style={{ borderWidth: 1, borderColor: color.inputBoxBorderGray, paddingVertical: 16, borderRadius: 12, marginVertical: 16, alignSelf: "center", marginHorizontal: 20, width: width - 40 }}>
                <View style={{ alignSelf: 'center',marginHorizontal:0 }}>
                    <Image source={{ uri: item?.get_property?.image }} style={{ height: 200, width: width-60, borderRadius: 12, marginHorizontal: 40 }} />
                    <Text numberOfLines={1} style={styles.title}>{item?.get_property?.title}</Text>
                    <View style={styles.status}>
                        <Text style={{textAlign:'center'}}>{item?.booking_status}</Text>
                    </View>
                    <View style={styles.host}>
                        <Text
                        //  onPress={()=>props.navigation.navigate('ChatMessage',{
                        //     // chatID:item?.chat_id,Book_ID:item.booking_id,sender_id:item?.guest_id,receiver_id:item?.id
                        //     'MyReservation':MyReservation
                        // })}
                          style={styles.hostTxt}>Host - {item?.get_property?.get_host_details[0]?.name}</Text>
                        <Text style={styles.booking_id}>#{item?.booking_id}</Text>
                    </View>
                    <View style={styles.host1}>
                        <Text>NGN {item?.per_night_price} X {item?.total_days} =</Text>
                        <Text style={styles.total}>NGN {Number(item?.per_night_price * item?.total_days)}</Text>
                    </View>
                    <View style={styles.host1}>
                        <Text style={{ color: color.primaryColorBlack }}>Caution Fee</Text>
                        <Text style={{ color: color.primaryColorBlack, fontSize: 15, fontWeight: '600' }}>NGN {item?.get_property?.security_deposit_amount}</Text>
                    </View>
                    <View
                        style={{ borderWidth: .5, borderColor: color.inputBoxBorderGray, marginVertical: 5, marginHorizontal: 40 }}
                    />
                    <View style={styles.host1}>
                        <Text style={{}}>Grand Total</Text>
                        <Text style={{ color: color.primaryColorBlack, fontSize: 15, fontWeight: '600' }}>NGN {Number(item?.per_night_price * item?.total_days) + Number(item?.get_property?.security_deposit_amount)}</Text>
                    </View>
                    <View
                        style={{ borderWidth: .5, borderColor: color.inputBoxBorderGray, marginVertical: 5, marginHorizontal: 40 }}
                    />
                    <View style={{ marginHorizontal: 40, flexDirection: 'row',width:width-100 }}>
                        <View style={{ marginHorizontal: 5 ,width:'35%'}}>
                            <Text style={{ fontSize: 10, color: color.primaryColorBlack }}>
                                Check-in - Check out
                            </Text>
                            <Text style={{ fontSize: 10 }}>
                                {moment(item?.from_date).format('MMM DD, yyyy')} / {moment(item?.to_date).format('MMM DD, yyyy')}
                            </Text>
                        </View>
                        <View
                            style={{ height: 30, width: 0.5, backgroundColor: 'gray' }}></View>
                        <View style={{ marginHorizontal: 10,width:'20%' }}>
                            <Text style={{ fontSize: 10, color: color.primaryColorBlack }}>
                                Who
                            </Text>
                            <Text style={{ fontSize: 10 }}>
                                {item?.no_of_adult_guest + item?.
                                    no_of_children_guest + item?.
                                        no_of_babies_guest + item?.
                                        no_of_pet} Guests
                            </Text>
                        </View>
                        <View
                            style={{ height: 30, width: 0.5, backgroundColor: 'gray' }}></View>
                        <View style={{ marginLeft: 5 ,width:'45%'}}>
                            <Text style={{ fontSize: 10, color: color.primaryColorBlack }}>
                                Reservation Date & Time
                            </Text>
                            <Text style={{ fontSize: 10 }}>
                                {moment(item?.created_at).format('MMM DD, yyyy')} / {moment(item?.created_at).format('hh:mm:a')}
                            </Text>
                        </View>
                    </View>
                </View>
            </View>
        )
    }
    return (

        <View style={{ flex: 1 }}>
            <Header
                props={props}
                Heading={'My Reservations'}
                onPress={() => props.navigation.navigate('Account')}
            />
            <View style={{ flex: 1, justifyContent: 'center', alignItems: 'center' }}>
                {
                    loader ? <View>
                        <ActivityIndicator size={'large'} color={color.appOrangeColor} />
                    </View>
                        :
                        reserveList == '' ? <View style={{ alignSelf: 'center', }}>
                            <Image source={Images.No_Data} style={{ height: 200, width: 200 }} />

                            <Text style={{ alignSelf: 'center' }}>No Data Found</Text>
                        </View>
                            :
                            <View style={{}}>
                                <FlatList
                                    data={reserveList || []}
                                    renderItem={renderItem}
                                />
                            </View>

                }
            </View>
        </View>
    )
}

export default MyReservation;

const styles = StyleSheet.create({
    title: {
        fontSize: 14,
        fontWeight: '600',
        marginHorizontal: 40,
        marginTop: 10,
        color: color.primaryColorBlack
    },
    status: {
        width: width - 200,
        backgroundColor: color.inputBoxBorderGray,
        marginHorizontal: 40,
        // paddingHorizontal: 20,
        borderRadius: 20,
        padding: 10,
        marginVertical:5
    },
    host: {
        flexDirection: "row",
        marginHorizontal: 40,
        marginVertical: 5,
        justifyContent: "flex-start",
        alignItems: "center"
    },
    host1: {
        flexDirection: "row",
        marginHorizontal: 40,
        marginVertical: 5,
        justifyContent: "space-between",
        alignItems: "center"
    },
    hostTxt: {
        color: color.primaryColorBlack
    },
    booking_id: {
        marginLeft: 20,
        color: color.primaryColorBlack,
        width:width/2-50
    },
    total: {
        color: color.primaryColorBlack
        , fontSize: 15, fontWeight: '600'
    }
})