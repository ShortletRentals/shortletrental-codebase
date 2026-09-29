import React from 'react';
import { Animated, FlatList, StyleSheet, useWindowDimensions, View,Text,Image } from 'react-native';
import FastImage from 'react-native-fast-image';
import { width } from '../styles/colors';

const Indicator = ({
    scrollX,
    data,
    dotStyle,
    containerStyle,
    inActiveDotOpacity,
    inActiveDotColor,
    expandingDotWidth,
    activeDotColor,
}) => {
    const { width } = useWindowDimensions();

    const defaultProps = {
        inActiveDotColor: inActiveDotColor || '#000',
        inActiveDotOpacity: inActiveDotOpacity || 0.5,
        expandingDotWidth: expandingDotWidth || 20,
        dotWidth: (dotStyle.width) || 10,
        activeDotColor: activeDotColor || 'orange',
    };

    return (
        <View
            pointerEvents={'none'}
            style={[styles.containerStyle, containerStyle]}
        >
            {data.map((_, index) => {
                const inputRange = [
                    (index - 1) * width,
                    index * width,
                    (index + 1) * width,
                ];

                const colour = scrollX.interpolate({
                    inputRange,
                    outputRange: [
                        defaultProps.inActiveDotColor,
                        defaultProps.activeDotColor,
                        defaultProps.inActiveDotColor,
                    ],
                    extrapolate: 'clamp',
                });
                const opacity = scrollX.interpolate({
                    inputRange,
                    outputRange: [
                        defaultProps.inActiveDotOpacity,
                        1,
                        defaultProps.inActiveDotOpacity,
                    ],
                    extrapolate: 'clamp',
                });
                const expand = scrollX.interpolate({
                    inputRange,
                    outputRange: [
                        defaultProps.dotWidth,
                        defaultProps.expandingDotWidth,
                        defaultProps.dotWidth,
                    ],
                    extrapolate: 'clamp',
                });

                return (
                    <Animated.View
                        key={`dot-${index}`}
                        style={[
                            styles.dotStyle,
                            dotStyle,
                            { width: expand },
                            { opacity },
                            { backgroundColor: colour },
                        ]}
                    />
                );
            })}
        </View>
    )
}


const CustomImageFlatList = ({ data }) => {
    const scrollX = React.useRef(new Animated.Value(0)).current;

    return (
        <>
            <FlatList
                horizontal
                initialNumToRender={1}
                pagingEnabled
                decelerationRate={'normal'}
                scrollEventThrottle={16}
                showsHorizontalScrollIndicator={false}
                data={data}
                keyExtractor={(_, index) => index.toString()}
                onScroll={Animated.event(
                    [{ nativeEvent: { contentOffset: { x: scrollX } } }],
                    {
                        useNativeDriver: false,
                    }
                )}
                
                renderItem={({ item ,index}) => (
                    
                   index<5 &&
                    <Image
                        style={{
                            borderTopLeftRadius: 10,
                            borderTopRightRadius: 10,
                            width: width * (332.3 / 375),
                            alignSelf: 'center',
                            height: width * (225 / 375),
                        }}
                        resizeMode="cover"
                        source={{ uri: item }} />
                    
                
                )}
            />

            <Indicator
                data={data.slice(0, 5)}
                scrollX={scrollX}
                expandingDotWidth={10}
                inActiveDotOpacity={0.6}
                colour={'orange'}
                dotStyle={{
                    width: 10,
                    height: 10,
                    backgroundColor: '#347af0',
                    borderRadius: 5,
                    marginHorizontal: 5
                }}
                containerStyle={{
                    top: width * (200 / 375),
                }} />
        </>
    )
}

const styles = StyleSheet.create({
    containerStyle: {
        position: 'absolute',
        bottom: 20,
        flexDirection: 'row',
        alignSelf: 'center',
    },
    dotStyle: {
        width: 10,
        height: 10,
        borderRadius: 5,
        marginHorizontal: 5,
    }
})

export default CustomImageFlatList;