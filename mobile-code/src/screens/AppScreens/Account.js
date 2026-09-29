import React, { useContext, useState } from 'react';
import {
    View,
    Image,
    Text,
    ImageBackground,
    TouchableOpacity,
    _Text,
    ScrollView,
    ActivityIndicator,
    SafeAreaView,
    BackHandler,
    Alert,
    InteractionManager,
} from 'react-native';
import { color, height, width } from '../../styles/colors';
import Images from '../../styles/Images';
import { commonStyles } from './../../styles/style';
import { Colors, colors } from 'react-native/Libraries/NewAppScreen';
import Header from '../../components/Header';
import TextView from '../../components/TextView';
import BottomTab from '../../navigators/BottomTab';
import {
    useFocusEffect,
    useRoute,
} from '@react-navigation/native';
import AsyncStorage from '@react-native-async-storage/async-storage';
import axios from 'axios';
import { User_Profile } from '../../network/Webconstant';
import {
    AllCourtShimmer,

} from '../../components/Skeleton';
import { RefreshControl } from 'react-native';
import { useEffect } from 'react';

let myToken = '';

const Account = props => {

    const [ProfileUserData, setProfileUserData] = useState({});
    const [loader, setLoader] = useState(false);
    const [editHandle, setEditHandle] = useState(false)
    const route = useRoute();
    const [refreshing, setRefreshing] = React.useState(false);


    useFocusEffect(
        React.useCallback(() => {
            const task = InteractionManager.runAfterInteractions(async () => {
                const Localtoken = await AsyncStorage.getItem('token_id');
               
                myToken = Localtoken

                getUserProfile()
            });
            return () => task.cancel();
        }, []),
    );

    const onRefresh = React.useCallback(() => {
        setRefreshing(true);
        getUserProfile()
        setTimeout(() => {
            setRefreshing(false);
        }, 2000);
    }, []);

    const handler = ()=>{
        props.navigation.navigate('Home')
    }
    
    useFocusEffect(
        React.useCallback(()=>{
            const backAction = () => {
                handler()
                return true;
            };
      
            const backHandler = BackHandler.addEventListener(
                'hardwareBackPress',
                backAction,
            );
      
            return () => backHandler.remove();
        },[props])
    )
    // useEffect(() => {
    //     const backAction = () => {
    //         handler()
    //         return true;
    //     };
  
    //     const backHandler = BackHandler.addEventListener(
    //         'hardwareBackPress',
    //         backAction,
    //     );
  
    //     return () => backHandler.remove();
    // }, [props])
    const getUserProfile = async () => {
     
        setLoader(true);
        const Localtoken = await AsyncStorage.getItem('token_id');
        // myToken = myToken ? Localtoken : null
        try {
            await axios({
                method: 'get',
                url: User_Profile,
                headers: {
                    Accept: 'application/json',
                    Authorization: `Bearer ${Localtoken}`,
                },
            })
                .then(function (res) {
                    console.log('-==--==res',res.data);
                    if (res.data.status) {
                        setLoader(false);
                        setProfileUserData(res.data.data);
                        setEditHandle(true)
                    } else {
                        setLoader(false);
                    }
                })
                .catch(e => {
                    console.log('error is', e);
                    setLoader(false);

                });
        } catch (error) {
            console.log('something went wrong', error);
            setLoader(false);
        }
    };

    // console.log('profileuser', ProfileUserData.notification_on_off);
    return (
        <View style={[commonStyles.container, { color: color.appWhiteColor }]}>

            <Header
                Heading={'My Account'}
                onPress={() => props.navigation.navigate('Home')}
            />
            {myToken == null ? (
                <View style={{ marginHorizontal: 20 }}>
                    <View style={{ marginTop: 107 }}>
                        <Image source={Images.MaskImage} style={{ height: 119, width: 142, alignSelf: 'center' }} />
                        <Text style={{ fontSize: 28, color: '#000', alignSelf: 'center', marginTop: 22 }}>Login or Sign Up</Text>
                        <Text style={{ fontSize: 18, color: '#393939', alignSelf: 'center', textAlign: 'center', padding: 20 }}>If you want to access all data then login first</Text>
                    </View>
                    <TouchableOpacity
                        // {onformLogout}
                        onPress={() => props.navigation.navigate('Logins')}
                        style={{
                            // marginTop: 30,
                            backgroundColor: color.appBlueColor,
                            width: '90%',
                            padding: 10,
                            borderRadius: 10,
                            justifyContent: 'center',
                            alignSelf: 'center',
                            marginBottom: width * (20 / 375),
                            height: 50,
                            marginHorizontal: 20,
                        }}>
                        <View
                            style={{
                                position: 'absolute',
                                flex: 1,
                            }}>
                            <Image
                                source={Images.whiteDot}
                                style={{
                                    paddingLeft: 70,
                                    width: 25,
                                    height: 25,
                                    resizeMode: 'contain',
                                }}
                            />
                        </View>

                        <Text
                            style={{
                                textAlign: 'center',
                                fontSize: 15,
                                color: color.appWhiteColor,
                            }}>
                            Login
                        </Text>
                    </TouchableOpacity>
                    <TouchableOpacity
                        // {onformLogout}
                        onPress={() => props.navigation.navigate('SignUp')}
                        style={{
                            // marginTop: 30,
                            backgroundColor: color.appBlueColor,
                            width: '90%',
                            padding: 10,
                            borderRadius: 10,
                            justifyContent: 'center',
                            alignSelf: 'center',
                            marginBottom: width * (20 / 375),
                            height: 50,
                            marginHorizontal: 20,
                        }}>
                        <View
                            style={{
                                position: 'absolute',
                                flex: 1,
                            }}>
                            <Image
                                source={Images.whiteDot}
                                style={{
                                    paddingLeft: 70,
                                    width: 25,
                                    height: 25,
                                    resizeMode: 'contain',
                                }}
                            />
                        </View>

                        <Text
                            style={{
                                textAlign: 'center',
                                fontSize: 15,
                                color: color.appWhiteColor,
                            }}>
                            Sign Up
                        </Text>
                    </TouchableOpacity>
                </View>
            ) : (
                <View>
                    {
                        editHandle ? <TouchableOpacity
                            onPress={() =>
                                props.navigation.navigate('EditProfile', {
                                    ProfileEmail: ProfileUserData.email,
                                    profileData: ProfileUserData,
                                    ProfileDob: ProfileUserData.dob,
                                })
                            }
                            style={{ top: -35, alignSelf: 'flex-end', marginHorizontal: 20 }}>
                            <Text style={{ fontSize: 18, color: color.white, fontWeight: '600' }}>
                                Edit
                            </Text>
                        </TouchableOpacity> : null
                    }
                    <ScrollView
                        showsVerticalScrollIndicator={false}
                        refreshControl={
                            <RefreshControl refreshing={refreshing} onRefresh={onRefresh} />
                        }

                    // contentContainerStyle={{ flexGrow: 1 }}
                    >
                        {loader ?
                            <AllCourtShimmer />
                            :
                            <View>
                                <View
                                    style={{
                                        width: 158,
                                        height: 158,
                                        // resizeMode: '',
                                        alignSelf: 'center',
                                        marginTop: 10,
                                        borderWidth: 2,
                                        borderRadius: 80,
                                        borderColor: '#00000080',
                                        backgroundColor: '#fff',
                                    }}>
                                    <Image
                                        // source={{ uri: ProfileUserData == undefined ? Images.ProfileImageIcons : ProfileUserData.image }}
                                        source={{ uri: ProfileUserData.image }}
                                        style={{
                                            width: 155,
                                            height: 155,
                                            alignSelf: 'center',
                                            borderRadius: 75,

                                        }}
                                        resizeMode='cover'
                                    />
                                </View>
                                <View style={{ alignItems: 'center', marginTop: 10 }}>
                                    <Text
                                        style={{
                                            fontSize: 20,
                                            fontWeight: '400',
                                            color: color.primaryColorBlack,
                                        }}>
                                        {ProfileUserData.name}
                                        {/* {myName == undefined ? ProfileUserData.name : myName} */}
                                    </Text>
                                    <Text
                                        style={{
                                            fontSize: 14,
                                            color: '#393939',
                                            marginTop: 5,
                                        }}>
                                        {ProfileUserData.email}
                                        {/* {myEmail == undefined ? ProfileUserData.email : myEmail} */}
                                    </Text>
                                    <Text
                                        style={{
                                            fontSize: 16,
                                            color: '#707070',
                                            marginTop: 5,
                                        }}>
                                        {/* {ProfileUserData == undefined ? USERDATA.dob : ProfileUserData.dob} */}
                                        {ProfileUserData.dob}
                                    </Text>
                                </View>

                                <View
                                    style={{
                                        marginTop: 5,
                                        backgroundColor: color.appWhiteColor,
                                        flexDirection: 'row',
                                        borderRadius: 10,
                                        alignItems: 'flex-start',
                                        alignSelf: 'center',
                                        // width: width * (330 / 375),
                                        height: width * (60 / 375),
                                        top: 10,
                                        borderWidth:1,
                                        borderColor:color.appOrangeColor,
                                        marginHorizontal:20,
                                        width:width-40
                                    }}>
                                    <View
                                        style={{
                                            borderRightWidth: 0.5,
                                            borderRightColor: color.appOrangeColor,
                                            justifyContent: 'center',
                                            marginHorizontal: 20,
                                            marginTop: 10,
                                        }}>
                                        <Text
                                            style={{
                                                color: color.primaryColorBlack,
                                                paddingRight: 95,
                                            }}>
                                            Gender
                                        </Text>
                                        <Text style={{ color: '#707070', fontSize: 16 }}>
                                            {/* {ProfileUserData == undefined ? USERDATA.gender : ProfileUserData.gender} */}
                                            {ProfileUserData.gender}
                                        </Text>
                                    </View>
                                    <View style={{ marginTop: 10, marginHorizontal: 10 }}>
                                        <Text
                                            style={{
                                                color: color.primaryColorBlack,
                                                paddingRight: 10,
                                            }}>
                                            Martial Status
                                        </Text>
                                        <Text style={{ fontSize: 16, color: '#707070' }}>
                                            {ProfileUserData.marital_status}
                                            {/* {ProfileUserData == undefined ? USERDATA.marital_status : ProfileUserData.marital_status} */}
                                        </Text>
                                    </View>
                                </View>
                            </View>
                        }
                        <View>
                            {/* <TouchableOpacity
                                onPress={() => props.navigation.navigate('MyCards')}
                                style={{
                                    borderRadius: 10,
                                    marginTop: 30,
                                    height: 60,
                                    marginHorizontal: 20,
                                    paddingHorizontal: 16,
                                    backgroundColor: color.appLightOrangeColor,
                                    borderColor: color.appOrangeColor,
                                    borderWidth: 1,
                                    flexDirection: 'row',
                                    justifyContent: 'space-between',
                                    alignItems: 'center',
                                }}>
                                <Text style={{ color: color.appOrangeColor, fontSize: 14 }}>
                                    My Cards
                                </Text>
                                <Image source={Images.ArrowIcons} />
                            </TouchableOpacity> */}
                            <TouchableOpacity
                                onPress={() => props.navigation.navigate('ChangePassword')}
                                style={{
                                    borderRadius: 10,
                                    marginTop: 30,
                                    height: 60,
                                    marginHorizontal: 20,
                                    paddingHorizontal: 16,
                                    backgroundColor: color.appLightOrangeColor,
                                    borderColor: color.appOrangeColor,
                                    borderWidth: 1,
                                    flexDirection: 'row',
                                    justifyContent: 'space-between',
                                    alignItems: 'center',
                                }}>
                                <Text style={{ color: color.appOrangeColor, fontSize: 14 }}>
                                    Change Password
                                </Text>
                                <Image source={Images.ArrowIcons} />
                            </TouchableOpacity>
                            <TouchableOpacity
                                onPress={() => props.navigation.navigate('Setting', { notification: ProfileUserData.notification_on_off })}
                                style={{
                                    borderRadius: 10,
                                    marginTop: 15,
                                    height: 60,
                                    marginHorizontal: 20,
                                    paddingHorizontal: 16,
                                    backgroundColor: color.appLightOrangeColor,
                                    borderColor: color.appOrangeColor,
                                    borderWidth: 1,
                                    flexDirection: 'row',
                                    justifyContent: 'space-between',
                                    alignItems: 'center',
                                }}>
                                <Text style={{ color: color.appOrangeColor, fontSize: 14 }}>
                                    Settings
                                </Text>
                                <Image source={Images.ArrowIcons} />
                            </TouchableOpacity>
                            <TouchableOpacity
                                onPress={() => props.navigation.navigate('LoyaltyPoints')}
                                style={{
                                    borderRadius: 10,
                                    marginTop: 15,
                                    height: 60,
                                    marginHorizontal: 20,
                                    paddingHorizontal: 16,
                                    backgroundColor: color.appLightOrangeColor,
                                    borderColor: color.appOrangeColor,
                                    borderWidth: 1,
                                    flexDirection: 'row',
                                    justifyContent: 'space-between',
                                    alignItems: 'center',
                                }}>
                                <Text style={{ color: color.appOrangeColor, fontSize: 14 }}>
                                    Loyalty Points
                                </Text>
                                <Image source={Images.ArrowIcons} />
                            </TouchableOpacity>
                            <TouchableOpacity
                                onPress={() => props.navigation.navigate('MyReservation')}
                                style={{
                                    borderRadius: 10,
                                    marginTop: 15,
                                    height: 60,
                                    marginHorizontal: 20,
                                    paddingHorizontal: 16,
                                    backgroundColor: color.appLightOrangeColor,
                                    borderColor: color.appOrangeColor,
                                    borderWidth: 1,
                                    flexDirection: 'row',
                                    justifyContent: 'space-between',
                                    alignItems: 'center',
                                }}>
                                <Text style={{ color: color.appOrangeColor, fontSize: 14 }}>
                                    My Reservations
                                </Text>
                                <Image source={Images.ArrowIcons} />
                            </TouchableOpacity>
                        </View>
                        <View style={{ marginBottom: 150 }} />
                    </ScrollView>


                </View>
            )}

            {/* <BottomTab /> */}
        </View>
    );
};

export default Account;
