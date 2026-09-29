import { BackHandler, FlatList, Image, StyleSheet, Text, View } from 'react-native'
import React, { useEffect } from 'react'
import Header from '../../components/Header'
import { useFocusEffect, useRoute } from '@react-navigation/native'
import { Rating } from 'react-native-ratings'
import Images from '../../styles/Images'
import { useState } from 'react'
import StarRating from 'react-native-star-rating'
import { color } from '../../styles/colors'
import moment from 'moment'
import { width } from '../../styles/style'

const ReviewScreen = (props) => {
    const route = useRoute()
    const Data = props.route?.params?.DATA
    // const [data, setData] = useState([route?.params?.DATA])
    // console.log('=========details', data);
    const [startCount, setStartCount] = useState(Data?.total_rating)
    const [count, setCount] = useState(0)

    const handler = () => {
        props.navigation.goBack()
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
// console.log('ratingg',Data.get_property_rating)
    
    const renderItem = (item) => {
        // console.log('============render', item?.item?.get_user?.image);
        return (
            <View style={{ marginHorizontal: 20 }}>
                <View style={{ flexDirection: 'row' }}>
                    {
                        item?.item?.get_user == null ?

                            <Image source={Images.House} style={{ height: 53, width: 53, borderRadius: 25 }} />
                            : <Image source={{ uri: item?.item?.get_user?.image }} style={{ height: 53, width: 53, borderRadius: 25 }} />
                    }
                    <View style={{ marginHorizontal: 10 }}>
                        <Text style={{ fontSize: 18, color: '#000', marginLeft: 6 }}>{item?.item?.get_user?.name}</Text>
                        <View
                            style={{
                                marginTop: 6,
                                marginBottom: 20,
                                // justifyContent: 'center',
                                // alignSelf: 'center',
                                flexDirection: 'row'
                            }}>
                            <StarRating
                                disabled={false}
                                emptyStar={Images.emptyStarImage}
                                fullStar={Images.StartBigIcon}

                                emptyStarColor={'#C1C1C1'}
                                maxStars={5}
                                rating={item?.item?.rate}
                                //   selectedStar={rating => onStarRatingPress(rating)}
                                starStyle={{ marginHorizontal: 4 }}
                                starSize={20}
                                containerStyle={{
                                    // marginHorizontal: 12,
                                    // alignSelf: 'flex-start',
                                    // margin: 8,
                                    // marginBottom: 14,
                                }}
                            />
                            <Text style={{ marginHorizontal: 8 }}>{item?.item?.rate}</Text>
                            <View>
                                <Text style={{ fontSize: 15, color: '#6B6B6B', marginHorizontal: 20 }}>{
                                    //  moment(new Date().getTime()).format('D') - moment(item?.item?.created_at).format('D')


                                    moment.utc(item.item.created_at).local().startOf('seconds').fromNow()
                                }
                                </Text>
                            </View>
                        </View>
                    </View>
                </View>
                <View style={{ marginBottom: 8 }}>
                    <Text style={{ fontSize: 14, color: '#393939' }}>{item?.item?.review}</Text>
                </View>

            </View>
        )
    }
    return (
        <View style={{ flex: 1, backgroundColor: '#fff' }}>
            <Header Heading={'Reviews'} onPress={() => props?.navigation?.goBack()} />
            <View>
                <Text style={{ alignSelf: 'center', fontSize: 51, color: '#000' }}>{Data.total_rating == null ? '0' : Data?.total_rating}.0</Text>

                <View
                    style={{
                        //   marginTop: 25,
                        marginBottom: 20,
                        justifyContent: 'center',
                        alignSelf: 'center'
                    }}>
                    <StarRating
                        disabled={false}
                        emptyStar={Images.emptyStarImage}
                        fullStar={Images.StartBigIcon}
                        // halfStar={'ios-star-half'}
                        // iconSet={Images.selectedIcons}
                        emptyStarColor={'#C1C1C1'}
                        // fullStarColor={'red'}
                        maxStars={5}
                        rating={startCount}
                        //   selectedStar={rating => onStarRatingPress(rating)}
                        starStyle={{ marginHorizontal: 12 }}
                        starSize={20}
                        containerStyle={{
                            marginHorizontal: 12,
                            alignSelf: 'flex-start',
                            // margin: 8,
                            // marginBottom: 14,
                        }}
                    />
                </View>
                <Text style={{ fontSize: 17, color: '#000', alignSelf: 'center' }}>Based on {Data?.total_rating} reviews</Text>
                <View style={{ flexDirection: "row", justifyContent: 'flex-start', alignItems: 'center', marginHorizontal: 20, width: width - 80, marginVertical: 5 }}>
                    <View style={{ width: "40%" }}>
                        <Text style={{ color: "#6B6B6B" }}>Excellent</Text>
                    </View>
                    <View style={{ flexDirection: "row", marginHorizontal: 20, width: '60%' }}>
                        <View style={{ backgroundColor: "green", height: 10, width: '80%' }} />
                        <View style={{ backgroundColor: "#E7E7E7", height: 10, width: '20%' }} />
                    </View>
                </View>
                <View style={{ flexDirection: "row", justifyContent: 'flex-start', alignItems: 'center', marginHorizontal: 20, width: width - 80 }}>
                    <View style={{ width: "40%" }}>

                        <Text style={{ color: "#6B6B6B" }}>Good</Text>
                    </View>
                    <View style={{ flexDirection: "row", marginHorizontal: 20, width: '60%' }}>
                        <View style={{ backgroundColor: "#A5D631", height: 10, width: '60%' }} />
                        <View style={{ backgroundColor: "#E7E7E7", height: 10, width: '40%' }} />
                    </View>
                </View>
                <View style={{ flexDirection: "row", marginVertical: 5, justifyContent: 'space-between', alignItems: 'center', marginHorizontal: 20, width: width - 80 }}>
                    <View style={{ width: "40%" }}>

                        <Text style={{ color: "#6B6B6B" }}>Average</Text>
                    </View>
                    <View style={{ flexDirection: "row", marginHorizontal: 20, width: '60%' }}>
                        <View style={{ backgroundColor: "#F7E632", height: 10, width: '50%' }} />
                        <View style={{ backgroundColor: "#E7E7E7", height: 10, width: '50%' }} />
                    </View>
                </View>
                <View style={{ flexDirection: "row", justifyContent: 'space-between', alignItems: 'center', marginHorizontal: 20, width: width - 80 }}>
                    <View style={{ width: "40%" }}>

                        <Text style={{ color: "#6B6B6B" }}>Below Average</Text>
                    </View>
                    <View style={{ flexDirection: "row", marginHorizontal: 20, width: '60%' }}>
                        <View style={{ backgroundColor: "#F7A828", height: 10, width: '40%' }} />
                        <View style={{ backgroundColor: "#E7E7E7", height: 10, width: '60%' }} />
                    </View>
                </View>
                <View style={{ flexDirection: "row", marginVertical: 5, justifyContent: 'space-between', alignItems: 'center', marginHorizontal: 20, width: width - 80 }}>
                    <View style={{ width: "40%" }}>

                        <Text style={{ color: "#6B6B6B" }}>Poor</Text>
                    </View>
                    <View style={{ flexDirection: "row", marginHorizontal: 20, width: '60%' }}>
                        <View style={{ backgroundColor: "red", height: 10, width: '30%' }} />
                        <View style={{ backgroundColor: "#E7E7E7", height: 10, width: '70%' }} />
                    </View>
                </View>
                <View style={{ borderWidth: 0.5, marginVertical: 10, borderColor: '#00000010' }} />

            </View>

            <View style={{ marginBottom: 50 }}>
                {
                    Data?.get_property_rating == [] || Data?.get_property_rating == ''
                    ?
                    <View style={{justifyContent:"center",alignItems:"center"}}>
                        <Image source={Images.No_Data} style={{height:200,width:200}}/>
                        <Text style={{ alignSelf: 'center' }}>No Data Found</Text>
                        </View>
                :
                <FlatList
                    data={Data?.get_property_rating}
                    renderItem={renderItem}
                />
                }
            </View>
        </View>

    )
}

export default ReviewScreen

const styles = StyleSheet.create({})

// https://we.tl/t-LBnC4lrMca